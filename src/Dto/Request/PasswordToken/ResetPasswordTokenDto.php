<?php

namespace App\Dto\Request\PasswordToken;

use App\Entity\User;

class ResetPasswordTokenDto
{
    public function __construct(
        public readonly User $user,
        public readonly string $token,
        public readonly int $lifetime
    ) {
    }
}
