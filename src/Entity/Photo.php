<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="photos")
 * @ORM\Entity(repositoryClass="App\Repository\PhotoRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Photo
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=64, unique=true)
     */
    private string $url;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Advert", inversedBy="photos")
     * @ORM\JoinColumn(name="advert_id", referencedColumnName="id")
     */
    private Advert $advert;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTime $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTime $updatedAt;

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