<?php

namespace App\Controller;

use App\Service\RegistrationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    private RegistrationService $registrationService;

    public function __construct(RegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
    }

    #[Route("/register", name: "register_action", methods: ["POST", "GET"])]
    public function registration(): Response
    {
        $user = $this->registrationService->registerNewUser();

        return new Response('done', 201);
    }

    #[Route("/verify", name: "verify_email", methods: ["GET"])]
    public function verifyEmail(Request $request): Response
    {
        $code = $request->get('code');
        $result = $this->registrationService->activateUserByVerificationCode($code);

        return new JsonResponse(['code' => $request->get('code'), 'status' => $result]);
    }

    #[Route("/user/{id}/reset-password")]
    public function resetPassword(): Response
    {
        return new JsonResponse(['action' => 'reset password']);
    }

    #[Route("/login", name: "login", methods: ["GET"])]
    public function login()
    {

    }

    public function logout()
    {

    }
}