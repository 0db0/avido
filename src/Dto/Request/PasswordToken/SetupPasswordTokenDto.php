<?php

namespace App\Dto\Request\PasswordToken;

final class SetupPasswordTokenDto
{
    public function __construct(
        public readonly int $userId,
        public readonly string $tokenId,
        public readonly int $lifetime
    ) {
    }
}
