<?php

namespace App\Enum;

enum UserStatus: int
{
    case Awaiting_email_activation = 0;
    case Active                    = 1;
    case Blocked                   = 2;
}