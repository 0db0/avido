<?php

namespace App\Service\Registration\TokenManager;

use App\Dto\Request\PasswordToken\SetupPasswordTokenDto;
use App\Entity\User;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

final class TokenGenerator
{
    public function __construct(private readonly ContainerBagInterface $params)
    {
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function generateToken(User $user): SetupPasswordTokenDto
    {
        $tokenId = Uuid::uuid4()->toString();
        $lifetime = $this->params->get('app.reset_password_token.lifetime');

        return new SetupPasswordTokenDto($user->getId(), $tokenId, $lifetime);
    }
}
