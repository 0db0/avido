<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Security\CustomAuthenticators\JWTAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class LoginController extends AbstractController
{
    public function __construct(private JWTAuthenticator $authenticator)
    {
    }

    #[Route('/login', name: 'login', methods: ['POST'])]
    public function index(#[CurrentUser] ?User $user): JsonResponse
    {
        return $this->json([
            'user' => $user?->getUserIdentifier(),
            'token' => 'asdasdasd',
        ]);
    }
}
