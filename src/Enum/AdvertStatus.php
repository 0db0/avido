<?php

namespace App\Enum;

enum AdvertStatus: int
{
    case draft      = 0;
    case moderation = 1;
    case rejected   = 2;
    case active     = 3;
    case done       = 4;
}
