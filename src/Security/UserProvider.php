<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @method void upgradePassword(PasswordAuthenticatedUserInterface|UserInterface $user, string $newHashedPassword)
 */
class UserProvider implements UserProviderInterface, PasswordUpgraderInterface
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {

        $this->userRepository = $userRepository;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $user = $this->userRepository->findBy(['email' => $identifier]);

        if (! $user) {
            throw new UserNotFoundException(sprintf('User with email %s not found', $identifier));
        }

        return $user;
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        return $this->loadUserByIdentifier($user->getUserIdentifier());
    }

    public function supportsClass(string $class): bool
    {
        return $class === User::class || is_subclass_of($class, User::class);
    }

    public function loadUserByUsername(string $username): UserInterface
    {
        return $this->loadUserByIdentifier($username);
    }
}