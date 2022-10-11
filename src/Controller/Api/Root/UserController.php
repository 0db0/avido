<?php

namespace App\Controller\Api\Root;

use App\Dto\Request\User\CreateUserDto;
use App\Service\RegistrationService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class UserController extends AbstractController
{
    public function __construct(
        private readonly RegistrationService $registrationService
    )
    {
    }

    #[Route('/user', name: 'root_create_user', methods: ['POST'])]
    #[ParamConverter('$createUserDto', CreateUserDto::class)]
    public function create(CreateUserDto $createUserDto): JsonResponse
    {
        $user = $this->registrationService->createNewUser($createUserDto);

        return $this->json([
            'message' => 'New user created.',
            'data'    => $user->toArray(),
        ], Response::HTTP_CREATED);
    }
}
