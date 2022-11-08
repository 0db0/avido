<?php

namespace App\Validator\TokenExists;

use App\Service\Registration\TokenManager\SetupPasswordTokenManager;
use App\Service\Registration\TokenManager\TokenStorage;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class TokenExistsValidator extends ConstraintValidator
{
    public function __construct(private readonly SetupPasswordTokenManager $tokenManager)
    {
    }

    public function validate($value, Constraint $constraint): void
    {
        if (! $constraint instanceof TokenExists) {
            throw new UnexpectedTypeException($constraint, TokenExists::class);
        }

        if (!is_string($value)) {
            $this->context->buildViolation($constraint->invalidTypeMessage)
                ->addViolation();
        }

        if (! $this->tokenManager->hasToken($value)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
