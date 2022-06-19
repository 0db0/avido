<?php

namespace App\MessageHandler;

use App\Message\EmailVerification;
use App\Repository\EmailVerificationRepository;
use App\Repository\UserRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class EmailVerificationHandler implements MessageHandlerInterface
{
    public function __construct(
        private MailerInterface             $mailer,
        private UserRepository              $userRepository,
        private EmailVerificationRepository $verificationRepository,
    ) {
    }

    public function __invoke(EmailVerification $message): void
    {
        $user = $this->userRepository->find($message->getUserId());
        $verificationCode = $this->verificationRepository->findOneBy(['user' => $user->getId()], ['createdAt' => 'DESC']);

        $email = (new TemplatedEmail())
            ->to($user->getEmail())
            ->htmlTemplate('emails/confirm_email.html.twig')
            ->context([
                'code' => $verificationCode->getCode(),
            ]);

        $this->mailer->send($email);
    }
}