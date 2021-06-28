<?php

namespace App\Service;

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

    public function registerNewUser(): User
    {
        $user = new User();
        $user->setEmail('asdas@masd.ru');
        $user->setFirstname('Foo');
        $user->setLastname('Bar');
        $user->setPhoneNumber('111111111');
        $user->setWhenConvenientReceiveCalls('Only message');
        $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));
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