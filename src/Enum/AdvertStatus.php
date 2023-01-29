<?php

namespace App\Enum;

enum AdvertStatus: int
{
    case Draft      = 0;
    case Moderation = 1;
    case Rejected   = 2;
    case Active     = 3;
    case Done       = 4;
}
