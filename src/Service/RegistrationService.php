<?php

namespace App\Service;

use App\Entity\EmailVerification;
use App\Entity\User;
use App\Event\UserRegisteredEvent;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationService
{
    private UserPasswordHasherInterface $passwordHasher;
    private EntityManagerInterface $manager;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $manager,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->passwordHasher = $passwordHasher;
        $this->manager = $manager;
        $this->eventDispatcher = $eventDispatcher;
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

    private function prepareCode(): string
    {
        try {
            return bin2hex(random_bytes(30));
        } catch (\Exception $e) {
            return Uuid::uuid4();
        }
    }
}