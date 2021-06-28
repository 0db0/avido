<?php

namespace App\EventListener;

use App\Event\UserRegisteredEvent;
use Psr\Log\LoggerInterface;

class RegistrationListener
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onUserRegistered(UserRegisteredEvent $event)
    {
        $verification = $event->getVerification();
//        $this->logger->info(sprintf('Verification code from Listener - %s', $verification->getCode()));
    }
}