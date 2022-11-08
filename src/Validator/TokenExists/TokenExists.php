<?php

namespace App\Validator\TokenExists;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class TokenExists extends Constraint
{
    public string $message            = 'The token is not exists.';
    public string $invalidTypeMessage = 'The token has invalid type.';
}
