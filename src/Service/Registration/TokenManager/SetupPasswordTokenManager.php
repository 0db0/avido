<?php

namespace App\Service\Registration\TokenManager;

use App\Dto\Request\PasswordToken\SetupPasswordTokenDto;
use App\Entity\User;
use App\Exception\TokenNotFoundException;
use JsonException;
use Psr\Cache\InvalidArgumentException;

class SetupPasswordTokenManager
{
    public function __construct(
        private readonly TokenGenerator $generator,
        private readonly TokenStorage $storage
    ) {
    }

    public function createToken(User $user): SetupPasswordTokenDto
    {
        $token = $this->generator->generateToken($user);
        $this->storage->setToken($token);

        return $token;
    }

    /**
     * @throws TokenNotFoundException|InvalidArgumentException|JsonException
     */
    public function getUserIdByToken(string $tokenId): int
    {
        $token = $this->storage->getToken($tokenId);

        if (!$token) {
            throw new TokenNotFoundException();
        }

        $payload = json_decode($token, false, 512, JSON_THROW_ON_ERROR);

        return $payload->user_id;
    }

    public function hasToken(string $tokenId): bool
    {
        return (bool) $this->storage->getToken($tokenId);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function deleteToken(string $key): void
    {
        $this->storage->purgeToken($key);
    }
}
