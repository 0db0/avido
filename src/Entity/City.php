<?php

namespace App\Entity;

use App\Repository\CityRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Table(name: 'cities')]
#[ORM\Entity(repositoryClass: CityRepository::class)]
#[ORM\HasLifecycleCallbacks]
class City
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(type: Types::STRING, length: 64)]
    private string $name;

    #[ORM\ManyToOne(Region::class)]
    #[ORM\JoinColumn(name: 'region_id', referencedColumnName: 'id')]
    private Region $region;

    #[ORM\Column(type: Types::STRING, length: 64)]
    private string $slug;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTime $createdAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTime $updatedAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getRegion(): Region
    {
        return $this->region;
    }

    public function setRegion(Region $region): void
    {
        $this->region = $region;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

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
}
