<?php

namespace App\Service\Advert\Moderation;

use App\Dto\Request\Advert\AdvertModerationDeclineDto;
use App\Entity\Advert;
use App\Entity\ModerationDecision;
use App\Enum\AdvertModerationStatus;
use App\Enum\AdvertStatus;
use Doctrine\ORM\EntityManagerInterface;

class Moderator
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function decline(Advert $advert, AdvertModerationDeclineDto $decisionDto): void
    {
        $decision = new ModerationDecision();
        $decision->setStatus(AdvertModerationStatus::Declined);

        if ($decisionDto->note) {
            $decision->setNote($decisionDto->note);
        }

        $advert->addModerationDecision($decision);
        $advert->setStatus(AdvertStatus::Rejected);

        $this->em->flush();
    }

    public function approve(Advert $advert): void
    {
        $decision = new ModerationDecision();
        $decision->setStatus(AdvertModerationStatus::Approved);

        $advert->addModerationDecision($decision);
        $advert->setStatus(AdvertStatus::Active);

        $this->em->flush();
    }
}
