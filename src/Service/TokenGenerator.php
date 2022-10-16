<?php

namespace App\Service;

use App\Dto\ResetPasswordTokenDto;
use App\Entity\User;

final class TokenGenerator
{
    public function generateResetToken(User $user): ResetPasswordTokenDto
    {
        $token = 'asdasd';

        return new ResetPasswordTokenDto($user, $token);
    }
}
