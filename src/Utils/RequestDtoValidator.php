<?php

namespace App\Utils;

use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestDtoValidator
{
    public function __construct(private ValidatorInterface $validator)
    {}

    public function validate(mixed $value): ConstraintViolationListInterface
    {
        return $this->validator->validate($value);
    }

    public function prepareMessage(ConstraintViolationListInterface $errors): string
    {
        $message = sprintf('You have %d violation constraints: ', $errors->count());

        /** @var ConstraintViolationInterface $error */
        foreach ($errors as $error) {
            $message .= $error->getMessage() . ' ';
        }

        return $message;
    }
}