<?php

namespace App\Entity;

use App\Enum\AdvertStatus;
use App\Repository\AdvertRepository;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'adverts')]
#[ORM\Entity(repositoryClass: AdvertRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Advert
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $name;

    #[ORM\ManyToOne(Category::class)]
    #[ORM\JoinColumn(name: 'category_id', referencedColumnName: 'id', nullable: false)]
    private Category $category;

    #[ORM\ManyToOne(City::class)]
    #[ORM\JoinColumn(name: 'city_id', referencedColumnName: 'id', nullable: false)]
    private City $city;

    #[ORM\Column(type: Types::TEXT)]
    private string $description;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private CarbonInterface $publishedAt;

    #[ORM\Column(type: Types::BIGINT, options: ['unsigned' => true])]
    private int $cost;

    #[ORM\ManyToOne(User::class)]
    #[ORM\JoinColumn(name: 'author_id', referencedColumnName: 'id', nullable: false)]
    private User $author;

    #[ORM\Column(type: Types::INTEGER, options: ['unsigned' => true])]
    private int $countViews;

    #[ORM\OneToMany(mappedBy: 'advert', targetEntity: Photo::class)]
    private Collection $photos;


    #[ORM\Column(type: Types::SMALLINT, options: ['unsigned' => true])]
    private int $status;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private CarbonInterface $createdAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private CarbonInterface $updatedAt;

    #[ORM\OneToMany(mappedBy: 'advert', targetEntity: ModerationDecision::class)]
    private Collection $moderationDecisions;

    public function __construct()
    {
        $this->countViews = 0;
        $this->photos = new ArrayCollection();
        $this->moderationDecisions = new ArrayCollection();
    }

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

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function setCategory(Category $category): void
    {
        $this->category = $category;
    }

    public function getCity(): City
    {
        return $this->city;
    }

    public function setCity(City $city): void
    {
        $this->city = $city;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getPublishedAt(): CarbonInterface
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(\DateTime $publishedAt): void
    {
        $this->publishedAt = Carbon::createFromTimestamp($publishedAt->getTimestamp());
    }

    public function getCost(): int
    {
        return $this->cost;
    }

    public function setCost(int $cost): void
    {
        $this->cost = $cost;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function setAuthor(User $author): void
    {
        $this->author = $author;
    }

    public function getCountViews(): int
    {
        return $this->countViews;
    }

    public function setCountViews(int $countViews): void
    {
        $this->countViews = $countViews;
    }

    public function getPhotos(): ArrayCollection
    {
        return $this->photos;
    }

    public function addPhoto(Photo $photo): void
    {
        $photo->setAdvert($this);
        if (! $this->photos->contains($photo)) {
            $this->photos->add($photo);
        }
    }

    public function getStatus(): AdvertStatus
    {
        return AdvertStatus::from($this->status);
    }

    public function setStatus(AdvertStatus $status): void
    {
        $this->status = $status->value;
    }

    public function getCreatedAt(): CarbonInterface
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setInitialTime(): void
    {
        $this->createdAt = $this->updatedAt = Carbon::now();
    }

    #[ORM\PreUpdate]
    public function refreshUpdatedAt(): void
    {
        $this->updatedAt = Carbon::now();
    }

    public function toArray(): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'status'      => AdvertStatus::from($this->status)->name,
            'author_id'   => $this->author->getId(),
            'category'    => $this->category->getName(),
            'city'        => $this->city->getName(),
            'description' => $this->description,
            'cost'        => $this->cost,
            'count_views' => $this->countViews,
            'created_at'  => $this->createdAt->toIso8601ZuluString(),
            'updated_at'  => $this->updatedAt->toIso8601ZuluString(),
        ];
    }

    public function getModerationDecisions(): Collection
    {
        return $this->moderationDecisions;
    }

    public function addModerationDecision(ModerationDecision $moderationDecision): self
    {
        if (!$this->moderationDecisions->contains($moderationDecision)) {
            $this->moderationDecisions->add($moderationDecision);
            $moderationDecision->setAdvert($this);
        }

        return $this;
    }
}
