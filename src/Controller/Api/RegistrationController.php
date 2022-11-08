<?php

namespace App\Controller\Api;

use App\Dto\Request\EmailVerifyDto;
use App\Dto\Request\Password\SetupPasswordDto;
use App\Dto\Request\User\RegisterUserDto;
use App\Service\Registration\UserRegistration;
use App\Service\VerifyEmailService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

final class RegistrationController extends AbstractController
{
    public function __construct(
        private readonly UserRegistration   $registrationService,
        private readonly VerifyEmailService $verifyEmailService,
    ) {
    }

    #[Route("/register", name: "register", methods: ["POST"])]
    #[ParamConverter("userDto", RegisterUserDto::class)]
    public function register(RegisterUserDto $userDto): JsonResponse
    {
        $user = $this->registrationService->registerNewUser($userDto);

        return $this->json([
            'message' => 'Register new user. Please proceed to email box to activate your account.',
            'data' => $user->toArray(),
        ], 201);
    }

    #[Route('/verify', name: 'verify_email', methods: ['GET'])]
    #[ParamConverter('verifyDto', EmailVerifyDto::class)]
    public function verifyUserEmail(EmailVerifyDto $verifyDto): JsonResponse
    {
        $verification = $this->verifyEmailService->getVerificationByCode($verifyDto->getCode());
        $this->registrationService->activateUserByVerification($verification);

        return $this->json(['message' => 'User activate successfully']);
    }

    #[Route("/password/setup", name: "password_setup", methods: ["POST"])]
    #[ParamConverter('dto', SetupPasswordDto::class)]
    public function setupPassword(SetupPasswordDto $dto): JsonResponse
    {
        $this->registrationService->setupPassword($dto);

        return $this->json([
            'message' => 'Password successfully set',
        ]);
    }
}
