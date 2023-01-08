<?php

namespace App\Dto\Request\Advert;

use App\Entity\Category;
use App\Entity\City;
use App\Utils\Attributes\Mapped;
use Symfony\Component\Validator\Constraints as Assert;

final class CreateAdvertDto
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255)]
    public readonly string $name;

    #[Assert\NotBlank]
    #[Mapped('name')]
    public readonly Category $category;

    #[Assert\NotBlank]
    #[Mapped('slug')]
    public readonly City $city;

    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 10000)]
    public readonly string $description;

    #[Assert\NotBlank]
    #[Assert\PositiveOrZero]
    public readonly int $cost;

    public function __construct(
        string $name,
        Category $category,
        City $city,
        string $description,
        int $cost,
    ) {
        $this->name        = $name;
        $this->category    = $category;
        $this->city        = $city;
        $this->description = $description;
        $this->cost        = $cost;
    }
}
