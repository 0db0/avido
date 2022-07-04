<?php

namespace App\Enum;

enum UserRole: string
{
    case ROLE_ADMIN     = 'ROLE_ADMIN';
    case ROLE_MODERATOR = 'ROLE_MODERATOR';
    case ROLE_USER      = 'ROLE_USER';
}