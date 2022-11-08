<?php

namespace App\Service\Registration\TokenManager;

use App\Dto\Request\PasswordToken\SetupPasswordTokenDto;
use App\Entity\User;
use Carbon\Carbon;
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
    public function setToken(SetupPasswordTokenDto $tokenDto): void
    {
        $this->cache->get(
            $tokenDto->tokenId,
            static function (CacheItemInterface $item) use ($tokenDto): string {
                $item->expiresAfter($tokenDto->lifetime);

                return json_encode(['user_id' => $tokenDto->userId], JSON_THROW_ON_ERROR);
            }
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    public function purgeToken(string $key): bool
    {
        return $this->cache->delete($key);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getToken(string $key): ?string
    {
        return $this->cache->get($key, static function (CacheItemInterface $item): void {
            $item->expiresAt(Carbon::now()->subDay());
        });
    }
}
