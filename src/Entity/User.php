<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks
 */
class User
{
    public const STATUS_AWAITING_EMAIL_ACTIVATION = 0;
    public const STATUS_ACTIVE                    = 1;
    public const STATUS_BLOCKED                   = 2;

    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $patronymic;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private string $email;

    /**
     * @ORM\Column(type="string", length=13, unique=true)
     */
    private string $phoneNumber;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $whenConvenientReceiveCalls;

    /**
     * @ORM\Column(type="smallint", options={"unsigned"=true})
     */
    private int $status;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTime $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTime $updatedAt;

    public function __construct()
    {
        $this->patronymic = '';
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): void
    {
        $this->firstname = $firstname;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    public function getPatronymic(): string
    {
        return $this->patronymic;
    }

    public function setPatronymic(string $patronymic): void
    {
        $this->patronymic = $patronymic;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function getWhenConvenientReceiveCalls(): string
    {
        return $this->whenConvenientReceiveCalls;
    }

    public function setWhenConvenientReceiveCalls(string $whenConvenientReceiveCalls): void
    {
        $this->whenConvenientReceiveCalls = $whenConvenientReceiveCalls;
    }

    public function getStatus(): int
    {
        return $this->status;
    }


    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @ORM\PrePersist
     */
    public function setAwaitingStatus(): void
    {
        $this->status = self::STATUS_AWAITING_EMAIL_ACTIVATION;
    }

    /**
     * @ORM\PrePersist
     */
    public function setInitialTime(): void
    {
        $this->createdAt = $this->updatedAt = new \DateTime();
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @ORM\PreUpdate
     */
    public function refreshUpdatedAt(): void
    {
        $this->updatedAt = new \DateTime();
    }
}