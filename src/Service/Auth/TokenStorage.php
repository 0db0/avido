<?php

namespace App\Service\Auth;

use App\Dto\Request\ResetPasswordDto;
use App\Dto\ResetPasswordTokenDto;
use App\Entity\User;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Contracts\Cache\CacheInterface;

class TokenStorage
{
    public function __construct(
        private CacheInterface $cache,
        private ContainerBagInterface $params
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

            $item->expiresAfter($this->params->get('app.reset_password_token.lifetime'));

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

        return $token === $dto->getToken();
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
