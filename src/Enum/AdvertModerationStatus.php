<?php

namespace App\Enum;

enum AdvertModerationStatus: int
{
    case Declined = 0;
    case Approved  = 1;
}
