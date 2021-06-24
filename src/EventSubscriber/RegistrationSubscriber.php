<?php

namespace App\EventSubscriber;

use App\Event\UserRegisteredEvent;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class RegistrationSubscriber implements EventSubscriberInterface
{
    private LoggerInterface $logger;
    private MailerInterface $mailer;

    public function __construct(LoggerInterface $logger, MailerInterface $mailer)
    {
        $this->logger = $logger;
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            UserRegisteredEvent::NAME => 'handleRegistration',
        ];
    }

    public function handleRegistration(UserRegisteredEvent $event)
    {
        $verification = $event->getVerification();
        $user = $event->getUser();

        $email = (new TemplatedEmail())
            ->to($user->getEmail())
            ->htmlTemplate('emails/confirm_email.html.twig')
            ->context([
                'code' => $verification->getCode(),
            ]);


        $this->mailer->send($email);
        $this->logger->info(sprintf('Verification code from Subscriber - %s', $verification->getCode()));
    }
}