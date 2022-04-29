<?php

namespace App\Controller;

use App\Dto\CreateUserDto;
use App\Dto\EmailVerifyDto;
use App\Dto\ResetPasswordDto;
use App\Dto\UpdatePasswordDto;
use App\Entity\EmailVerification;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Message\TestMessage;
use App\Service\RegistrationService;
use App\Service\VerifyEmailService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    public function __construct(
        private RegistrationService $registrationService,
        private VerifyEmailService $verifyEmailService,
        private MessageBusInterface $bus,
    ) {
    }

    #[Route('/register', name: 'show_register', methods: ["GET"])]
    public function showRegister(): Response
    {
        $form = $this->createForm(RegistrationFormType::class);

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route("/register", name: "register", methods: ["POST"])]
    #[ParamConverter("userDto", CreateUserDto::class)]
    public function register(CreateUserDto $userDto): Response
    {
        $this->registrationService->registerNewUser($userDto);

        return new Response('register new user', 201);
    }

    #[Route('/verify', name: 'app_verify_email', methods: ['GET'])]
    #[ParamConverter('verifyDto', EmailVerifyDto::class)]
    public function verifyUserEmail(EmailVerifyDto $verifyDto): Response
    {
        $verification = $this->verifyEmailService->getVerificationByCode($verifyDto->getCode());
        $this->registrationService->activateUserByVerification($verification);

        return new Response('User activate successfully', 200);
    }

    #[Route("/reset-password", name: "reset_password", methods: ['POST'])]
    #[ParamConverter('resetPasswordDto', ResetPasswordDto::class)]
    public function resetPassword(): Response
    {


        return new Response('Requested reset password');
    }

    #[Route("/user/{id}/update-password", name: "update_password", methods: ['POST'])]
    public function updatePassword(User $user, UpdatePasswordDto $updatePasswordDto): Response
    {
        $this->registrationService->updatePassword($user, $updatePasswordDto);
        return new Response('Password updated successfully');
    }

    #[Route("/test", name: "test", methods: ["GET"])]
    public function get(): Response
    {
        $this->bus->dispatch(new TestMessage(55));

        return new Response('test', 201);
    }
}
