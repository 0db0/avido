<?php

namespace App\Dto;

use App\Entity\User;

class ResetPasswordTokenDto
{
    public function __construct(
        public readonly User $user,
        public readonly string $token
    ) {
    }
}
