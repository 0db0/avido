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

    #[Route('/login', name: 'login', methods: ['GET'])]
    public function index(#[CurrentUser] ?User $user): JsonResponse
    {
//        dd(
//            $this->authenticator
//        );

        return $this->json([
            'user' => $user?->getUserIdentifier(),
            'token' => 'asdasdasd',
        ]);
    }

    #[Route('/post', name: 'post', methods: ['GET'])]
    public function post(#[CurrentUser] ?User $user): JsonResponse
    {
        dd(
            $this->container->get('security.token_storage')->getToken()
        );

        return $this->json([
            'post' => 'achieved',
        ]);
    }
}
