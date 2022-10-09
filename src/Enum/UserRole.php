<?php

namespace App\Enum;

enum UserRole: string
{
    case Admin     = 'ROLE_ADMIN';
    case Moderator = 'ROLE_MODERATOR';
    case User      = 'ROLE_USER';
}