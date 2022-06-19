<?php

namespace App\Service\Auth;

use App\Dto\Request\ForgotPasswordDto;
use App\Dto\Request\ResetPasswordDto;
use App\Dto\ResetPasswordTokenDto;
use App\Exception\DeletePasswordTokenException;
use App\Exception\InvalidResetPasswordTokenException;
use App\Repository\UserRepository;
use App\Service\TokenGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class AuthService
{
    public function __construct(
        private UserRepository $userRepository,
        private TokenGenerator $tokenGenerator,
        private TokenStorage $tokenStorage,
        private MailerInterface $mailer,
        private UserPasswordHasherInterface $passwordHasher,
        private EntityManagerInterface $em
    ) {
    }

    /**
     * @throws EntityNotFoundException
     * @throws InvalidResetPasswordTokenException|InvalidArgumentException
     * @throws DeletePasswordTokenException
     */
    public function resetPassword(ResetPasswordDto $dto): void
    {
        $user = $this->userRepository->findOneBy(['email' => $dto->getEmail()]);

        if (! $user) {
            throw new EntityNotFoundException(sprintf('User with email %s not found', $dto->getEmail()));
        }

        if (! $this->tokenStorage->isTokenValid($dto, $user)) {
            throw new InvalidResetPasswordTokenException();
        }

        $this->em->beginTransaction();

        $user->setPassword($this->passwordHasher->hashPassword($user, $dto->getPassword()));
        $this->em->flush();

        $isRemoveSuccessful = $this->tokenStorage->purgeToken($user);

        if (! $isRemoveSuccessful) {
            $this->em->rollback();

            throw new DeletePasswordTokenException();
        }
    }

    /**
     * @throws EntityNotFoundException
     * @throws InvalidArgumentException
     */
    public function createResetPasswordToken(ForgotPasswordDto $dto): ResetPasswordTokenDto
    {
        $user = $this->userRepository->findOneBy(['email' => $dto->getEmail()]);

        if (!$user) {
            throw new EntityNotFoundException(sprintf('User with email %s not found', $dto->getEmail()));
        }

        $token = $this->tokenGenerator->generateResetToken($user);
        $this->tokenStorage->persistResetPasswordTokenToUser($token, $user);

        return $token;
    }

    public function sendResetPasswordEmail(ResetPasswordTokenDto $dto): void
    {
        $mail = (new TemplatedEmail())
            ->from()
            ->to($dto->getUser()->getEmail())
            ->htmlTemplate('emails.reset_password_email')
            ->context([
                'token' => $dto->getToken(),
            ]);

        $this->mailer->send($mail);
    }
}