<?php

namespace App\Service\Advert;

use App\Dto\Request\Advert\CreateAdvertDto;
use App\Dto\Request\Advert\UpdateAdvertDto;
use App\Email\Advert\PushToModerationEmail;
use App\Entity\Advert;
use App\Entity\User;
use App\Enum\AdvertStatus;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

final class AdvertService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly MailerInterface $mailer,
        private readonly ContainerBagInterface $params
    ) {
    }

    public function create(CreateAdvertDto $dto, User $user): Advert
    {
        $advert = new Advert();
        $advert->setName($dto->name);
        $advert->setCategory($dto->category);
        $advert->setCity($dto->city);
        $advert->setDescription($dto->description);
        $advert->setCost($dto->cost);
        $advert->setStatus(AdvertStatus::Draft);
        $advert->setAuthor($user);

        $this->em->persist($advert);
        $this->em->flush();

        return $advert;
    }

    public function update(UpdateAdvertDto $dto, Advert $advert): Advert
    {
        $advert->setName($dto->name);
        $advert->setCategory($dto->category);
        $advert->setCity($dto->city);
        $advert->setDescription($dto->description);
        $advert->setCost($dto->cost);

        $this->em->flush();

        return clone $advert;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function moveToModeration(Advert $advert): void
    {
        $advert->setStatus(AdvertStatus::Moderation);
        $this->em->flush();

        $addressee = $this->params->get('app.moderation.email');

        $this->mailer->send(PushToModerationEmail::build($advert, $addressee));
    }
}
