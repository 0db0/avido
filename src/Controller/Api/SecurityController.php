<?php

namespace App\Controller\Api;

use App\Dto\CreateUserDto;
use App\Dto\Request\Password\ForgotPasswordDto;
use App\Dto\Request\Password\ResetPasswordDto;
use App\Dto\Request\Password\SetupPasswordDto;
use App\Dto\Request\Password\UpdatePasswordDto;
use App\Entity\User;
use App\Service\Auth\AuthService;
use App\Service\Registration\UserRegistration;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    public function __construct(private readonly AuthService $authService)
    {
    }

    #[Route('/password/forgot', name: 'forgot_password', methods: ['POST'])]
    #[ParamConverter('dto', ForgotPasswordDto::class)]
    public function forgotPassword(ForgotPasswordDto $dto): JsonResponse
    {
        $token = $this->authService->createResetPasswordToken($dto);
        $this->authService->sendResetPasswordEmail($token);

        return $this->json(['message' => 'Reset password link was successfully send.']);
    }

    #[Route("/password/reset", name: "show_reset_password", methods: ['GET'])]
    public function showResetPassword(): JsonResponse
    {
        return $this->json(['message' => 'Show Password Reset Form']);
    }


    #[Route("/password/reset", name: "reset_password", methods: ['POST'])]
    #[ParamConverter('resetPasswordDto', ResetPasswordDto::class)]
    public function resetPassword(ResetPasswordDto $resetPasswordDto): JsonResponse
    {
        $this->authService->resetPassword($resetPasswordDto);

        return $this->json(['message' => 'Password successfully reset']);
    }

    #[Route("/user/{id}/password/update", name: "update_password", methods: ['POST'])]
    #[ParamConverter('updatePasswordDto', UpdatePasswordDto::class)]
    public function updatePassword(User $user, UpdatePasswordDto $updatePasswordDto): Response
    {
        $this->authService->updatePassword($user, $updatePasswordDto);

        return $this->json(['message' => 'Password updated successfully']);
    }


    public function logout(): void
    {
    }
}
