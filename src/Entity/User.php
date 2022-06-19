<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public const STATUS_AWAITING_EMAIL_ACTIVATION = 0;
    public const STATUS_ACTIVE = 1;
    public const STATUS_BLOCKED = 2;

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
     * @ORM\Column(type="string", length=13, unique=true, nullable=true)
     */
    private string $phoneNumber;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private string $whenConvenientReceiveCalls;

    /**
     * @ORM\Column(type="smallint", options={"unsigned"=true})
     */
    private int $status;

    /**
     * @ORM\Column(type="string")
     */
    private string $password;

    /**
     * @ORM\Column(type="json")
     */
    private array $roles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\EmailVerification", mappedBy="user")
     */
    private Collection $verifications;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTime $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTime $updatedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isVerified = false;

    public function __construct()
    {
        $this->patronymic = '';
        $this->roles = [];
        $this->verifications = new ArrayCollection();
    }

    public function getVerifications(): Collection
    {
        return $this->verifications;
    }

    public function addVerification(EmailVerification $verification): void
    {
        $verification->setUser($this);

        if (!$this->verifications->contains($verification)) {
            $this->verifications->add($verification);
        }
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

    public function getRoles(): array
    {
        $roles = $this->roles;

        if (empty($roles)) {
            $roles[] = 'ROLE_USER';
        }

        return array_unique($roles);
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
    }

    public function getUsername(): string
    {
        return $this->email;
    }

    public function getUserIdentifier(): string
    {
        return $this->getEmail();
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id'         => $this->id,
            'first_name' => $this->firstname,
            'last_name'  => $this->lastname,
            'email'      => $this->email,
            'status'     => $this->status,
            'created_at' => $this->createdAt,
        ];
    }
}