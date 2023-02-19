<?php

namespace App\Entity;

use App\Enum\AdvertModerationStatus;
use App\Repository\ModerationDecisionRepository;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ModerationDecisionRepository::class)]
#[ORM\HasLifecycleCallbacks]
class ModerationDecision
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Advert::class, inversedBy: 'moderationDecisions')]
    #[ORM\JoinColumn(nullable: false)]
    private Advert $advert;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $note = null;

    #[ORM\Column(type: Types::SMALLINT, options: ['unsigned' => true])]
    private int $status;

    #[ORM\Column]
    private CarbonInterface $createdAt;

    #[ORM\Column]
    private CarbonInterface $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdvert(): Advert
    {
        return $this->advert;
    }

    public function setAdvert(Advert $advert): self
    {
        $this->advert = $advert;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getStatus(): AdvertModerationStatus
    {
        return AdvertModerationStatus::from($this->status);
    }

    public function setStatus(AdvertModerationStatus $status): self
    {
        $this->status = $status->value;

        return $this;
    }

    public function getCreatedAt(): CarbonInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): CarbonInterface
    {
        return $this->updatedAt;
    }

    #[ORM\PrePersist]
    public function setInitialDateTime(): self
    {
        $this->updatedAt = $this->createdAt = Carbon::now();

        return $this;
    }

    #[ORM\PreUpdate]
    public function refreshUpdatedAt(): self
    {
        $this->updatedAt = Carbon::now();

        return $this;
    }
}
