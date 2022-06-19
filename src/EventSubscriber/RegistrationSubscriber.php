<?php

namespace App\EventSubscriber;

use App\Event\UserRegisteredEvent;
use App\Message\EmailVerification;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class RegistrationSubscriber implements EventSubscriberInterface
{
    public function __construct(private MessageBusInterface $messageDispatcher)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            UserRegisteredEvent::NAME => 'handleRegistration',
        ];
    }

    public function handleRegistration(UserRegisteredEvent $event): void
    {
//        $email = (new TemplatedEmail())
//            ->to($event->getUser()->getEmail())
//            ->htmlTemplate('emails/confirm_email.html.twig')
//            ->context([
//                'code' => $event->getVerification()->getCode(),
//            ]);
//        try {
//            $this->messageDispatcher->dispatch(new EmailVerification($email));
//        } catch (\Throwable $e) {
//            dd($e->getMessage());
//        }
    }
}