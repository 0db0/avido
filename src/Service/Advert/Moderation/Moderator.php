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
        $advert->setStatus(AdvertStatus::Rejected);
        /** @var ModerationDecision $decision */
        $decision = $advert->getModerationDecisions()->first();
        $decision->setStatus(AdvertModerationStatus::Declined);

        if ($decisionDto->note) {
            $decision->setNote($decisionDto->note);
        }

        $this->em->flush();
    }
}
