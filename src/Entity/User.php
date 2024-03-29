<?php

namespace App\Entity;

use App\Enum\UserRole;
use App\Enum\UserStatus;
use App\Repository\UserRepository;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Table(name: 'users')]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $firstname;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $lastname;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $patronymic;

    #[ORM\Column(type: Types::STRING, length: 255, unique: true)]
    private string $email;

    #[ORM\Column(type: Types::STRING, length: 13, unique: true, nullable: true)]
    private string $phoneNumber;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private string $whenConvenientReceiveCalls;

    #[ORM\Column(type: Types::SMALLINT, options: ['unsigned' => true])]
    private int $status;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private string $password;

    #[ORM\Column(type: Types::JSON)]
    private array $roles;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: EmailVerification::class)]
    private Collection $verifications;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private CarbonInterface $createdAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private CarbonInterface $updatedAt;

    #[ORM\Column(type: Types::BOOLEAN)]
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

    public function getStatus(): UserStatus
    {
        return UserStatus::from($this->status);
    }


    public function setStatus(UserStatus $status): void
    {
        $this->status = $status->value;
    }

    public function getCreatedAt(): CarbonInterface
    {
        return $this->createdAt;
    }
//
//    /**
//     * @ORM\PrePersist
//     */
//    public function setAwaitingStatus(): void
//    {
//        $this->status = UserStatus::Awaiting_email_activation->value;
//    }

    #[ORM\PrePersist]
    public function setInitialTime(): void
    {
        $this->createdAt = $this->updatedAt = Carbon::now();
    }

    public function getUpdatedAt(): CarbonInterface
    {
        return $this->updatedAt;
    }

    #[ORM\PreUpdate]
    public function refreshUpdatedAt(): void
    {
        $this->updatedAt = Carbon::now();
    }

    public function getRoles(): array
    {
        $roles = $this->roles;

        if (empty($roles)) {
            $roles[] = UserRole::User->value;
        }

        return array_unique($roles);
    }

    /**
     * @param UserRole[] $roles
     */
    public function setRoles(array $roles): void
    {
        $roles = array_map(static fn (UserRole $v): string => $v->value, $roles);
        $this->roles = array_unique($roles);
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

    public function getFullName(): string
    {
        return $this->firstname . $this->patronymic . $this->lastname;
    }

    public function toArray(): array
    {
        return [
            'id'         => $this->id,
            'first_name' => $this->firstname,
            'last_name'  => $this->lastname,
            'email'      => $this->email,
            'status'     => $this->status,
            'created_at' => $this->createdAt->toIso8601ZuluString(),
        ];
    }
}
