<?php

namespace App\Entity;

use App\Repository\RegionRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table('regions')]
#[ORM\Entity(RegionRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Region
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(type: Types::STRING, length: 64)]
    private string $name;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTime $createdAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTime $updatedAt;

    #[ORM\PrePersist]
    public function setInitialTime(): void
    {
        $this->createdAt = $this->updatedAt = new DateTime();
    }

    #[ORM\PreUpdate]
    public function setUpdatedAt(): void
    {
        $this->updatedAt = new DateTime();
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
