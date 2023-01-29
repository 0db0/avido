<?php

namespace App\Service\Auth;

use App\Dto\Request\Password\ResetPasswordDto;
use App\Dto\Request\PasswordToken\ResetPasswordTokenDto;
use App\Entity\User;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\Cache\CacheInterface;

class TokenStorage
{
    public function __construct(
        private readonly CacheInterface $cache
    ) {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function persistResetPasswordTokenToUser(ResetPasswordTokenDto $tokenDto, User $user): void
    {
        $key = 'password_reset_' . $user->getId();

        $this->cache->get($key, function (CacheItemInterface $item) use ($tokenDto): string {
            if ($item->isHit()) {
                return $item->get();
            }

            $item->expiresAfter($tokenDto->lifetime);

            return $tokenDto->token;
        });
    }

    /**
     * @throws InvalidArgumentException
     */
    public function isTokenValid(ResetPasswordDto $dto, User $user): bool
    {
        $key = 'password_reset' . $user->getId();
        $token = $this->cache->get($key, null);

        if (is_null($token)) {
            return false;
        }

        return $token === $dto->token;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function purgeToken(User $user): bool
    {
        $key = 'password_reset' . $user->getId();

        return $this->cache->delete($key);
    }
}
