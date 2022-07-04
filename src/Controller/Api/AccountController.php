<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class AccountController extends AbstractController
{
    public function __construct(private Security $security)
    {
    }

    #[Route("/account", name: "account", methods: ["GET"])]
    public function account(Request $request): JsonResponse
    {
        $user = $this->security->getUser();

        return $this->json([
            'data' => $user->toArray(),
        ]);
    }
}