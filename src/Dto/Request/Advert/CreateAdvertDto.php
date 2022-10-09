<?php

namespace App\Dto\Request\Advert;

use App\Entity\Category;
use App\Entity\City;
use App\Utils\Attributes\Mapped;

final class CreateAdvertDto
{
    public readonly string $name;
    public readonly int $cost;
    #[Mapped('name')]
    public readonly Category $category;
    #[Mapped('name')]
    public readonly City $city;
    public readonly string $description;

    public function __construct(
        string $name,
        Category $category,
        City $city,
        string $description,
        int $cost,
    ) {
        $this->description = $description;
        $this->city = $city;
        $this->category = $category;
        $this->cost = $cost;
        $this->name = $name;
    }
}
