<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="email_verifications")
 * @ORM\Entity(repositoryClass="App\Repository\EmailVerificationRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class EmailVerification
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     */
    private string $code;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="verifications")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private User $user;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private \DateTime $verified_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTime $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
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

    /**
     * @ORM\PrePersist
     */
    public function setInitialTime(): void
    {
        $this->createdAt = $this->updatedAt = new \DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedAt(): void
    {
        $this->updatedAt = new \DateTime();
    }
}