<?php

namespace App\MessageHandler;

use App\Message\EmailVerification;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class EmailVerificationHandler implements MessageHandlerInterface
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function __invoke(EmailVerification $message)
    {
        $this->mailer->send($message->getEmail());
    }
}