<?php

declare(strict_types=1);

namespace App\Utils\Attributes;

use Attribute;

#[Attribute]
class Mapped
{
    public function __construct(public readonly string $mappedKey)
    {
    }
}
