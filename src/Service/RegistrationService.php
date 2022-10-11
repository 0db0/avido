<?php

namespace App\Service;

use App\Dto\Request\UpdatePasswordDto;
use App\Dto\Request\User\AbstractCreateUserDto;
use App\Dto\Request\User\CreateUserDto;
use App\Dto\Request\User\RegisterUserDto;
use App\Entity\EmailVerification;
use App\Entity\User;
use App\Enum\UserRole;
use App\Enum\UserStatus;
use App\Message\EmailVerification as VerificationMessage;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationService
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly EntityManagerInterface      $manager,
        private readonly MailerInterface             $mailer,
        private readonly MessageBusInterface         $bus
    ) {
    }

    public function registerNewUser(RegisterUserDto $dto): User
    {
        $user = $this->makeNewUser($dto);
        $user->setPassword($this->passwordHasher->hashPassword($user, $dto->password));
        $user->setStatus(UserStatus::Awaiting_email_activation);
        $verification = $this->generateVerificationCode($user);

        $this->manager->persist($user);
        $this->manager->persist($verification);
        $this->manager->flush();

//        $this->mailer->send();

        $this->bus->dispatch(new VerificationMessage($user->getId()));

        return $user;
    }

    public function createNewUser(CreateUserDto $dto): User
    {
        $user = $this->makeNewUser($dto);

        if ($dto->getRole()) {
            $user->setRoles([$dto->getRole()]);
        }

        $user->setStatus(UserStatus::Awaiting_email_activation);

        $this->manager->persist($user);
        $this->manager->flush();

//        $this->mailer->send();

// todo: send mail to set password.
        return $user;
    }

    public function registerNewAdmin(RegisterUserDto $dto): User
    {
        $admin = $this->makeNewUser($dto);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, $dto->password));
        $admin->setStatus(UserStatus::Active);
        $admin->setIsVerified(true);
        $admin->setRoles([UserRole::Admin]);

        $this->manager->persist($admin);
        $this->manager->flush();

        return $admin;
    }

    private function makeNewUser(AbstractCreateUserDto $dto): User
    {
        $user = new User();
        $user->setFirstname($dto->firstName);
        $user->setLastname($dto->lastName);
        $user->setEmail($dto->email);

        return $user;
    }

    public function generateVerificationCode(User $user): EmailVerification
    {
        $verification = new EmailVerification();
        $verification->setCode($this->prepareCode());
        $verification->setUser($user);

        return $verification;
    }

    public function updatePassword(User $user, UpdatePasswordDto $dto): void
    {
        $user->setPassword($this->passwordHasher->hashPassword($user, $dto->getPassword()));

        $this->manager->flush();
    }

    public function activateUserByVerification(EmailVerification $verification): void
    {
        $verification->setVerifiedAt(new \DateTime());
        $verification->getUser()->setStatus(UserStatus::Active);
        $this->manager->flush();
    }

    private function prepareCode(): string
    {
        try {
            return bin2hex(random_bytes(30));
        } catch (\Exception $e) {
            return Uuid::uuid4();
        }
    }
}
