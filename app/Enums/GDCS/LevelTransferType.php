<?php

namespace App\Enums\GDCS;

enum LevelTransferType: int
{
    case OFFICIAL = 1;
    case OLD_GDCN = 2;
    case OUT = 3;
}
