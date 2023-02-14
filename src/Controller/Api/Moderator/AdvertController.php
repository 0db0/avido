<?php

namespace App\Controller\Api\Moderator;

use App\Dto\Request\Advert\AdvertModerationDeclineDto;
use App\Entity\Advert;
use App\Security\Permissions\AdvertPermissions;
use App\Service\Advert\Moderation\Moderator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;


final class AdvertController extends AbstractController
{
    public function __construct(
        private readonly Moderator $moderator,
    ) {
    }

    #[Route('/adverts/{id}', name: 'show_advert', methods: ['GET'])]
    public function show(Advert $advert): JsonResponse
    {
        $this->denyAccessUnlessGranted(AdvertPermissions::SHOW, $advert);

        return $this->json([
            'message' => 'Advert fetched',
            'data'    => $advert->toArray(),
        ]);
    }

    #[Route('/adverts/{id}/decline', name: 'push_to_moderation_advert', methods: ['POST'])]
    public function approve(Advert $advert): JsonResponse
    {
        $this->denyAccessUnlessGranted(AdvertPermissions::MODERATE, $advert);


        return $this->json([
            'message' => 'Advert moved to moderation',
            'data'    => $advert->toArray(),
        ]);
    }

    #[Route('/adverts/{id}/decline', name: 'decline_advert', methods: ['POST'])]
    #[ParamConverter('decision', AdvertModerationDeclineDto::class)]
    public function decline(Advert $advert, AdvertModerationDeclineDto $decisionDto): JsonResponse
    {
        $this->denyAccessUnlessGranted(AdvertPermissions::MODERATE, $advert);

        $this->moderator->decline($advert, $decisionDto);

        return $this->json([
            'message' => 'Advert moved to moderation',
            'data'    => $advert->toArray(),
        ]);
    }
}
