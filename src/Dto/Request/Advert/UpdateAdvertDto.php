<?php

declare(strict_types=1);

namespace App\Dto\Request\Advert;

use App\Dto\Request\RequestDtoInterface;
use App\Entity\Category;
use App\Entity\City;
use App\Utils\Attributes\QueryBy;
use Symfony\Component\Validator\Constraints as Assert;

final class UpdateAdvertDto implements RequestDtoInterface
{
    #[Assert\Length(min: 3, max: 255)]
    public readonly string|null $name;

    #[QueryBy('name')]
    public readonly Category|null $category;

    #[QueryBy('slug')]
    public readonly City|null $city;

    #[Assert\Length(min: 3, max: 10000)]
    public readonly string|null $description;

    #[Assert\PositiveOrZero]
    public readonly int|null $cost;

    public function __construct(
        ?string $name,
        ?Category $category,
        ?City $city,
        ?string $description,
        ?int $cost,
    ) {
        $this->name        = $name;
        $this->category    = $category;
        $this->city        = $city;
        $this->description = $description;
        $this->cost        = $cost;
    }
}
