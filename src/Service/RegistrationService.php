<?php

namespace App\Service;

use App\Dto\Request\CreateUserDto;
use App\Dto\Request\UpdatePasswordDto;
use App\Entity\EmailVerification;
use App\Entity\User;
use App\Enum\UserRole;
use App\Enum\UserStatus;
use App\Message\EmailVerification as VerificationMessage;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationService
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private EntityManagerInterface $manager,
        private MessageBusInterface $bus
    ) {
    }

    public function registerNewUser(CreateUserDto $dto): User
    {
        $user = $this->makeNewUser($dto);
        $user->setStatus(UserStatus::Awaiting_email_activation);
        $verification = $this->generateVerificationCode($user);

        $this->manager->persist($user);
        $this->manager->persist($verification);
        $this->manager->flush();

        $this->bus->dispatch(new VerificationMessage($user->getId()));

        return $user;
    }

    public function registerNewAdmin(CreateUserDto $dto): User
    {
        $admin = $this->makeNewUser($dto);
        $admin->setStatus(UserStatus::Active);
        $admin->setIsVerified(true);
        $admin->setRoles([UserRole::Admin]);

        $this->manager->persist($admin);
        $this->manager->flush();

        return $admin;
    }

    private function makeNewUser(CreateUserDto $dto): User
    {
        $user = new User();
        $user->setFirstname($dto->getFirstName());
        $user->setLastname($dto->getLastName());
        $user->setEmail($dto->getEmail());
        $user->setPassword($this->passwordHasher->hashPassword($user, $dto->getPassword()));

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
        $verification->getUser()->setStatus(User::STATUS_ACTIVE);
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