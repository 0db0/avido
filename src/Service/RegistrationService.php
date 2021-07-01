<?php

namespace App\Service;

use App\Dto\CreateUserDto;
use App\Entity\EmailVerification;
use App\Entity\User;
use App\Event\UserRegisteredEvent;
use App\Repository\EmailVerificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Ramsey\Uuid\Uuid;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationService
{
    private UserPasswordHasherInterface $passwordHasher;
    private EntityManagerInterface $manager;
    private EventDispatcherInterface $eventDispatcher;
    private EmailVerificationRepository $verificationRepository;

    public function __construct(
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $manager,
        EventDispatcherInterface $eventDispatcher,
        EmailVerificationRepository $verificationRepository
    ) {
        $this->passwordHasher = $passwordHasher;
        $this->manager = $manager;
        $this->eventDispatcher = $eventDispatcher;
        $this->verificationRepository = $verificationRepository;
    }

    public function registerNewUser(CreateUserDto $dto): User
    {
        $user = new User();
        $user->setFirstname($dto->getFirstName());
        $user->setLastname($dto->getLastName());
        $user->setEmail($dto->getEmail());
        $user->setPassword($this->passwordHasher->hashPassword($user, $dto->getPassword()));
        $user->setPhoneNumber($dto->getPhoneNumber());
        $user->setWhenConvenientReceiveCalls($dto->getWhenConvenientReceiveCalls());
        $user->setStatus(User::STATUS_AWAITING_EMAIL_ACTIVATION);
        $verification = $this->generateVerificationCode($user);

        $this->manager->persist($user);
        $this->manager->persist($verification);
        $this->manager->flush();

        $this->eventDispatcher->dispatch(new UserRegisteredEvent($user, $verification), UserRegisteredEvent::NAME);

        return $user;
    }

    public function generateVerificationCode(User $user): EmailVerification
    {
        $verification = new EmailVerification();
        $verification->setCode($this->prepareCode());
        $verification->setUser($user);

        return $verification;
    }

    public function activateUserByVerificationCode(string $code): bool
    {
        /** @var EmailVerification $verification */
        $verification = $this->verificationRepository->findOneBy([
            'code' => $code,
            'verified_at' => null
        ], [
            'createdAt' => 'DESC'
        ]);

        if (! $verification) {
            throw new EntityNotFoundException(sprintf('Unknown verification code: %s', $code));
        }

        if (! $this->isCodeExpired($verification)) {
            $verification->setVerifiedAt(new \DateTime());
            $verification->getUser()->setStatus(User::STATUS_ACTIVE);
            $this->manager->flush();

            return true;
        }

        return false;
    }

    private function prepareCode(): string
    {
        try {
            return bin2hex(random_bytes(30));
        } catch (\Exception $e) {
            return Uuid::uuid4();
        }
    }

    private function isCodeExpired(EmailVerification $verification): bool
    {
        $now = (new \DateTime())->getTimestamp();
        $createdAt = $verification->getCreatedAt()->getTimestamp();

        return $now - $createdAt > 3600;
    }
}