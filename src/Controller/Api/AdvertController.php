<?php

namespace App\Controller\Api;

use App\Dto\Request\Advert\CreateAdvertDto;
use App\Entity\Advert;
use App\Security\Permissions\AdvertPermissions;
use App\Service\Advert\AdvertService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
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

    #[Route('/adverts/{id}', name: 'show_advert', methods: ['GET'])]
    public function show(Advert $advert): JsonResponse
    {
        $this->denyAccessUnlessGranted(AdvertPermissions::SHOW, $advert);

        return $this->json([
            'message' => 'Advert fetched',
            'data'    => $advert->toArray(),
        ]);
    }

    #[Route('/adverts/{id}', name: 'edit_advert', methods: ['PUT'])]
    public function update(Advert $advert): JsonResponse
    {
        $this->denyAccessUnlessGranted(AdvertPermissions::EDIT, $advert);

        return $this->json([
            'message' => 'Advert updated.',
            'id'      => $advert->toArray(),
        ]);
    }

    #[Route('/adverts/{id}', name: 'delete_advert', methods: ['DELETE'])]
    public function delete(Advert $advert): JsonResponse
    {
        $this->denyAccessUnlessGranted(AdvertPermissions::DELETE, $advert);

        return $this->json([], Response::HTTP_NO_CONTENT);
    }
}
