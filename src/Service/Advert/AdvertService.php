<?php

namespace App\Service\Advert;

use App\Dto\Request\Advert\CreateAdvertDto;
use App\Entity\Advert;
use App\Entity\User;
use App\Enum\AdvertStatus;
use Doctrine\ORM\EntityManagerInterface;

final class AdvertService
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function create(CreateAdvertDto $dto, User $user): Advert
    {
        $advert = new Advert();
        $advert->setName($dto->name);
        $advert->setCategory($dto->category);
        $advert->setCity($dto->city);
        $advert->setDescription($dto->description);
        $advert->setCost($dto->cost);
        $advert->setStatus(AdvertStatus::draft->value);
        $advert->setSeller($user);

        $this->em->persist($advert);
        $this->em->flush();

        return $advert;
    }
}
