<?php

namespace App\Dto\Request\Advert;

use App\Entity\Category;
use App\Entity\City;

final class CreateAdvertDto
{
    public function __construct(
        public readonly string $name,
        public readonly Category $category,
        public readonly City $city,
        public readonly string $description,
        public readonly int $cost,
    ) {
    }
}
