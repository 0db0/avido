<?php

namespace App\Entity;

use App\Repository\EmailVerificationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table('email_verifications')]
#[ORM\Entity(repositoryClass: EmailVerificationRepository::class)]
#[ORM\HasLifecycleCallbacks]
class EmailVerification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(type: Types::STRING, length: 80, unique: true)]
    private string $code;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'verifications')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private User $user;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private \DateTime $verified_at;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTime $createdAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTime $updatedAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getVerifiedAt(): \DateTime
    {
        return $this->verified_at;
    }

    public function setVerifiedAt(\DateTime $verified_at): void
    {
        $this->verified_at = $verified_at;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
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
