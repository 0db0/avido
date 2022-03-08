<?php

namespace App\Service;

use App\Entity\EmailVerification;
use App\Entity\User;
use App\Exception\ExpiredEmailConformationException;
use App\Repository\EmailVerificationRepository;
use Doctrine\ORM\EntityNotFoundException;

class VerifyEmailService
{
    public function __construct(private EmailVerificationRepository $verificationRepository)
    {
    }

    public function validateEmailCode(string $code): void
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

        if ($this->isCodeExpired($verification)) {
            throw new ExpiredEmailConformationException('Code was expired');
        }
    }

    private function isCodeExpired(EmailVerification $verification): bool
    {
        $now = (new \DateTime())->getTimestamp();
        $createdAt = $verification->getCreatedAt()->getTimestamp();

        return $now - $createdAt > 3600;
    }
}