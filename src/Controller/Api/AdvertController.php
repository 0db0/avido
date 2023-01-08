<?php

namespace App\Controller\Api;

use App\Dto\Request\Advert\CreateAdvertDto;
use App\Dto\Request\Advert\UpdateAdvertDto;
use App\Entity\Advert;
use App\Entity\User;
use App\Security\Permissions\AdvertPermissions;
use App\Service\Advert\AdvertService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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
    #[ParamConverter("createDto", CreateAdvertDto::class)]
    public function create(CreateAdvertDto $createDto): JsonResponse
    {
        $this->denyAccessUnlessGranted(AdvertPermissions::CREATE);
        $advert = $this->advertService->create($createDto, $this->getUser());

        return $this->json([
            'message' => 'Advert successfully created.',
            'data'    => $advert->toArray(),
        ], Response::HTTP_CREATED);
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
    #[ParamConverter("updateDto", UpdateAdvertDto::class)]
    public function update(Advert $advert, UpdateAdvertDto $updateDto): JsonResponse
    {
        $this->denyAccessUnlessGranted(AdvertPermissions::EDIT, $advert);
        $updatedAdvert = $this->advertService->update($updateDto, $advert);

        return $this->json([
            'message' => 'Advert updated.',
            'data'    => $updatedAdvert->toArray(),
        ]);
    }

    #[Route('/adverts/{id}', name: 'delete_advert', methods: ['DELETE'])]
    public function delete(Advert $advert): JsonResponse
    {
        $this->denyAccessUnlessGranted(AdvertPermissions::DELETE, $advert);

        return $this->json([], Response::HTTP_NO_CONTENT);
    }

    #[Route('/adverts/{id}', name: 'push_to_moderation_advert', methods: ['POST'])]
    public function pushToModeration(Advert $advert): JsonResponse
    {
        $this->denyAccessUnlessGranted(AdvertPermissions::PUSH_TO_MODERATION, $advert);
        $this->advertService->moveToModeration($advert);

        return $this->json([
            'message' => 'Advert moved to moderation',
            'data'    => $advert->toArray(),
        ]);
    }
}
