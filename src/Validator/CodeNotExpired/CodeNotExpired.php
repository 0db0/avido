<?php

namespace App\Validator\CodeNotExpired;

use Symfony\Component\Validator\Constraint;

#[\Attribute] class CodeNotExpired extends Constraint
{
    public string $message = 'The code was expired.';
    public string $notFoundCodeMessage = 'The code was not found.';
}
