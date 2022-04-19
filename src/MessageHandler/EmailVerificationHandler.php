<?php

namespace App\MessageHandler;

use App\Message\EmailVerification;
use App\Repository\EmailVerificationRepository;
use App\Repository\UserRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class EmailVerificationHandler implements MessageHandlerInterface
{
    public function __construct(
        private MailerInterface             $mailer,
        private UserRepository              $userRepository,
        private EmailVerificationRepository $verificationRepository,
        private LoggerInterface             $logger
    ) {
    }

    public function __invoke(EmailVerification $message): void
    {
        $user = $this->userRepository->find($message->getUserId());
        $verificationCode = $this->verificationRepository->findOneBy(['user' => $user->getId()], ['createdAt' => 'DESC']);
//sleep(10);
        $email = (new TemplatedEmail())
            ->to($user->getEmail())
            ->htmlTemplate('emails/confirm_email.html.twig')
            ->context([
                'code' => $verificationCode->getCode(),
            ]);

        $this->logger->info(sprintf('Verification code from Subscriber - %s', $user->getEmail()));
        $this->mailer->send($email);
    }
}