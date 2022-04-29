<?php

namespace App\Service\Auth;

use App\Dto\ResetPasswordDto;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityNotFoundException;

final class AuthService
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    /**
     * @throws EntityNotFoundException
     */
    public function resetPassword(ResetPasswordDto $dto): void
    {
        $user = $this->userRepository->findOneBy(['email' => $dto->getEmail()]);

        if (!$user) {
            throw new EntityNotFoundException(sprintf('User with email %s not found', $dto->getEmail()));
        }

//        todo: implements reset password token storage by redis
    }
}