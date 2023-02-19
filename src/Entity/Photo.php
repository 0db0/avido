<?php

namespace App\Entity;

use App\Repository\PhotoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table('photos')]
#[ORM\Entity(repositoryClass: PhotoRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Photo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(type: Types::STRING, length: 64, unique: true)]
    private string $url;

    #[ORM\ManyToOne(targetEntity: Advert::class, inversedBy: 'photos')]
    #[ORM\JoinColumn(name: 'advert_id', referencedColumnName: 'id')]
    private Advert $advert;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTime $createdAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTime $updatedAt;

    #[ORM\PrePersist]
    public function setInitialTime(): void
    {
        $this->createdAt = $this->updatedAt = new \DateTime();
    }

    #[ORM\PreUpdate]
    public function setUpdatedAt(): void
    {
        $this->updatedAt = new \DateTime();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function getAdvert(): Advert
    {
        return $this->advert;
    }

    public function setAdvert(Advert $advert): void
    {
        $this->advert = $advert;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

}
