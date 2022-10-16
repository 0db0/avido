<?php

namespace App\Controller\Api\Root;

use App\Dto\Request\User\CreateUserDto;
use App\Dto\Request\User\UpdateUserDto;
use App\Entity\User;
use App\Service\Registration\UserRegistration;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class UserController extends AbstractController
{
    public function __construct(
        private readonly UserRegistration $registrationService
    ) {
    }

    #[Route('/user', name: 'root_create_user', methods: ['POST'])]
    #[ParamConverter('createUserDto', CreateUserDto::class)]
    public function create(CreateUserDto $createUserDto): JsonResponse
    {
        $user = $this->registrationService->createNewUser($createUserDto);

        return $this->json([
            'message' => 'New user created.',
            'data'    => $user->toArray(),
        ], Response::HTTP_CREATED);
    }

    #[Route('/user/{id}', name: 'root_create_user', methods: ['PATCH'])]
    #[ParamConverter('updateUserDto', UpdateUserDto::class)]
    public function update(User $user, UpdateUserDto $updateUserDto): JsonResponse
    {
        $user = $this->registrationService->updateUser($user, $updateUserDto);

        return $this->json([
            'message' => 'User updated.',
            'data'    => $user->toArray(),
        ]);
    }

    #[Route('/user/{id}', name: 'root_delete_user', methods: ['DELETE'])]
    public function delete(User $user): JsonResponse
    {
        $this->registrationService->delete($user);

        return $this->json([], Response::HTTP_NO_CONTENT);
    }
}
