<?php

namespace App\Utils\Attributes;

use Attribute;

#[Attribute]
class RequestFieldAliases
{
    public function __construct(public readonly array $aliases)
    {
    }
}
