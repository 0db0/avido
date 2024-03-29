<?php

namespace App\Service;

use App\Dto\Request\PasswordToken\ResetPasswordTokenDto;
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
    public function generateResetToken(User $user): ResetPasswordTokenDto
    {
        $token = Uuid::uuid4()->toString();
        $lifetime = $this->params->get('app.reset_password_token.lifetime');

        return new ResetPasswordTokenDto($user, $token, $lifetime);
    }
}
