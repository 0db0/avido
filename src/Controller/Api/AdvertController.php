<?php

namespace App\Controller\Api;

use App\Dto\Request\Advert\CreateAdvertDto;
use App\Security\Permissions\AdvertPermissions;
use App\Service\Advert\AdvertService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;


final class AdvertController extends AbstractController
{
    public function __construct(
        private readonly AdvertService $advertService,
    ) {
    }

    #[Route('/advert', name: 'create_advert', methods: ['POST'])]

    public function create(CreateAdvertDto $createDto): JsonResponse
    {
        $this->denyAccessUnlessGranted(AdvertPermissions::CREATE);
        $advert = $this->advertService->create($createDto, $this->getUser());

        return $this->json([
            'message' => 'Advert successfully fetched.',
            'data'    => $advert->toArray(),
        ]);
    }
}
