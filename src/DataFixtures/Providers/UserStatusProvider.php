<?php

namespace App\DataFixtures\Providers;

use App\Enum\UserStatus;

class UserStatusProvider
{
    public function userStatus(int $intStatus): ?UserStatus
    {
        return UserStatus::tryFrom($intStatus);
    }
}
