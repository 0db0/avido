<?php

namespace App\Service;

use App\Entity\EmailVerification;
use App\Repository\EmailVerificationRepository;
use Doctrine\ORM\EntityNotFoundException;

class VerifyEmailService
{
    private const VERIFICATION_CODE_PERIOD_EXPIRED = 3600;

    public function __construct(private EmailVerificationRepository $verificationRepository)
    {
    }

    public function getVerificationByCode(string $code): EmailVerification
    {
        /** @var EmailVerification $verification */
        $verification = $this->verificationRepository->findOneBy([
            'code'        => $code,
            'verified_at' => null,
        ], [
            'createdAt' => 'DESC',
        ]);

        if (! $verification) {
            throw new EntityNotFoundException(sprintf('Unknown verification code: %s', $code));
        }

        return $verification;
    }
}
