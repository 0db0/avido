<?php

namespace App\Controller\Api;

use App\Dto\Request\CreateUserDto;
use App\Dto\Request\EmailVerifyDto;
use App\Dto\Request\ForgotPasswordDto;
use App\Dto\Request\ResetPasswordDto;
use App\Dto\Request\UpdatePasswordDto;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Service\Auth\AuthService;
use App\Service\RegistrationService;
use App\Service\VerifyEmailService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    public function __construct(
        private RegistrationService $registrationService,
        private VerifyEmailService $verifyEmailService,
        private AuthService $authService,
    ) {
    }

    #[Route("/register", name: "api_register", methods: ["POST"])]
    #[ParamConverter("userDto", CreateUserDto::class)]
    public function register(CreateUserDto $userDto): JsonResponse
    {
        $user = $this->registrationService->registerNewUser($userDto);

        return new JsonResponse([
            'message' => 'Register new user. Please proceed to email box to activate your account.',
            'data' => $user->toArray(),
        ], 201);
    }

    #[Route('/verify', name: 'api_verify_email', methods: ['GET'])]
    #[ParamConverter('verifyDto', EmailVerifyDto::class)]
    public function verifyUserEmail(EmailVerifyDto $verifyDto): JsonResponse
    {
        $verification = $this->verifyEmailService->getVerificationByCode($verifyDto->getCode());
        $this->registrationService->activateUserByVerification($verification);

        return new JsonResponse(['message' => 'User activate successfully']);
    }

    #[Route('/forgot-password', name: 'api_forgot_password', methods: ['POST'])]
    #[ParamConverter('dto', ForgotPasswordDto::class)]
    public function forgotPassword(ForgotPasswordDto $dto): JsonResponse
    {
        $token = $this->authService->createResetPasswordToken($dto);
        $this->authService->sendResetPasswordEmail($token);

       return new JsonResponse([
            'data' => [
                'message' => 'Reset password link was successfully send.',
            ]
        ]);
    }

    #[Route("/reset-password", name: "api_show_reset_password", methods: ['GET'])]
    public function showResetPassword(): JsonResponse
    {
        return new JsonResponse([
            'data' => [
                'message' => 'Shoe Password Reset Form',
            ],
        ]);
    }


    #[Route("/reset-password", name: "api_reset_password", methods: ['POST'])]
    #[ParamConverter('resetPasswordDto', ResetPasswordDto::class)]
    public function resetPassword(ResetPasswordDto $resetPasswordDto): JsonResponse
    {
        $this->authService->resetPassword($resetPasswordDto);

        return new JsonResponse([
            'data' => [
                'message' => 'Password successfully reset',
            ],
        ]);
    }

    #[Route("/user/{id}/update-password", name: "update_password", methods: ['POST'])]
    #[ParamConverter('updatePasswordDto', UpdatePasswordDto::class)]
    public function updatePassword(User $user, UpdatePasswordDto $updatePasswordDto): Response
    {
        $this->registrationService->updatePassword($user, $updatePasswordDto);
        return new Response('Password updated successfully');
    }
}
