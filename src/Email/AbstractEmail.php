<?php

namespace App\Email;

use Symfony\Component\Mime\Email;

abstract class AbstractEmail extends Email
{
    abstract public static function build(): static;
}
