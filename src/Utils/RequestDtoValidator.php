<?php

namespace App\Utils;

use ReflectionException;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use TypeError;

class RequestDtoValidator
{
    public function __construct(private readonly ValidatorInterface $validator)
    {
    }

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

    public function prepareMessageFromException(ReflectionException|TypeError $e, array $attributes): string
    {
        $message = 'Missing required field';
        foreach ($attributes as $attribute => $key) {
            if (str_contains($e->getMessage(), '$'.$attribute)) {
                $message .= ' '. $attribute;
            }
        }

        return $message;
    }
}
