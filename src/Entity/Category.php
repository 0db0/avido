<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'categories')]
#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;


    #[ORM\Column(type: Types::STRING, length: 128)]
    private string $name;

    #[ORM\Column(type: Types::STRING, length: 64, unique: true)]
    private string $urlCode;

    #[ORM\Column(type: Types::TEXT)]
    private string $description;

    #[ORM\Column(type: Types::INTEGER)]
    private int $parentId;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTime $createdAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTime $updatedAt;

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

    public function getUrlCode(): string
    {
        return $this->urlCode;
    }

    public function setUrlCode(string $urlCode): void
    {
        $this->urlCode = $urlCode;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getParentId(): int
    {
        return $this->parentId;
    }

    public function setParentId(int $parentId): void
    {
        $this->parentId = $parentId;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

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
}
