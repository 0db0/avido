<?php

namespace App\Service\Auth;

use App\Dto\Request\Password\ForgotPasswordDto;
use App\Dto\Request\Password\ResetPasswordDto;
use App\Dto\Request\Password\UpdatePasswordDto;
use App\Dto\Request\PasswordToken\ResetPasswordTokenDto;
use App\Email\Password\ResetPasswordEmail;
use App\Entity\User;
use App\Exception\DeletePasswordTokenException;
use App\Exception\InvalidResetPasswordTokenException;
use App\Repository\UserRepository;
use App\Service\TokenGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Psr\Cache\InvalidArgumentException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class AuthService
{
    public function __construct(
        private readonly UserRepository              $userRepository,
        private readonly TokenGenerator              $tokenGenerator,
        private readonly TokenStorage                $tokenStorage,
        private readonly MailerInterface             $mailer,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly EntityManagerInterface      $em
    ) {
    }

    /**
     * @throws EntityNotFoundException
     * @throws InvalidResetPasswordTokenException|InvalidArgumentException
     * @throws DeletePasswordTokenException
     */
    public function resetPassword(ResetPasswordDto $dto): void
    {
        $user = $this->userRepository->findOneBy(['email' => $dto->email]);

        if (! $user) {
            throw new EntityNotFoundException(sprintf('User with email %s not found', $dto->email));
        }

        if (! $this->tokenStorage->isTokenValid($dto, $user)) {
            throw new InvalidResetPasswordTokenException();
        }

        $this->em->beginTransaction();

        $user->setPassword($this->passwordHasher->hashPassword($user, $dto->password));
        $this->em->flush();

        $isRemoveSuccessful = $this->tokenStorage->purgeToken($user);

        if (! $isRemoveSuccessful) {
            $this->em->rollback();

            throw new DeletePasswordTokenException();
        }
    }

    /**
     * @throws EntityNotFoundException
     */
    public function createResetPasswordToken(ForgotPasswordDto $dto): ResetPasswordTokenDto
    {
        $user = $this->userRepository->findOneBy(['email' => $dto->email]);

        if (!$user) {
            throw new EntityNotFoundException(sprintf('User with email %s not found', $dto->email));
        }

        $token = $this->tokenGenerator->generateResetToken($user);
        $this->tokenStorage->persistResetPasswordTokenToUser($token, $user);

        return $token;
    }

    public function sendResetPasswordEmail(ResetPasswordTokenDto $dto): void
    {
        $this->mailer->send(
            ResetPasswordEmail::build($dto->token, $dto->user->getEmail())
        );
    }

    public function updatePassword(User $user, UpdatePasswordDto $dto): void
    {
        $user->setPassword($this->passwordHasher->hashPassword($user, $dto->password));

        $this->em->flush();
    }
}
