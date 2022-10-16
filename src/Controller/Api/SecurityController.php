<?php

namespace App\Controller\Api;

use App\Dto\CreateUserDto;
use App\Service\Registration\UserRegistration;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SecurityController extends AbstractController
{
    public function __construct(private readonly UserRegistration $registrationService)
    {
    }

//    #[Route("/verify", name: "verify_email", methods: ["GET"])]
//    public function verifyEmail(Request $request): Response
//    {
//        $code = $request->get('code');
//        $result = $this->registrationService->activateUserByVerificationCode($code);
//
//        return new JsonResponse(['code' => $request->get('code'), 'status' => $result]);
//    }

//    #[Route("/user/{id}/reset-password", name: "reset_password", methods: ['POST'])]
//    public function resetPassword(): Response
//    {
//        return new JsonResponse(['action' => 'reset password']);
//    }

//    #[Route("/login", name: "login", methods: ["GET"])]
//    public function login()
//    {
//
//    }

    public function logout()
    {
    }
}
