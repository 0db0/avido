<?php

namespace App\Validator\CodeNotExpired;

use App\Repository\EmailVerificationRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CodeNotExpiredValidator extends ConstraintValidator
{
    private const VERIFICATION_CODE_PERIOD_EXPIRED = 3600;

    public function __construct(private readonly EmailVerificationRepository $verificationRepository)
    {
    }

    public function validate($value, Constraint $constraint): void
    {
        /* @var $constraint CodeNotExpired */

        $verification = $this->verificationRepository->findOneBy([
            'code'        => $value,
            'verified_at' => null,
        ], [
            'createdAt' => 'DESC',
        ]);

        if (! $verification) {
            $this->context->buildViolation($constraint->notFoundCodeMessage)
                ->addViolation();
            return;
        }

        $createdAt = $verification->getCreatedAt();
        if ((new \DateTime())->diff($createdAt)->s > self::VERIFICATION_CODE_PERIOD_EXPIRED) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
