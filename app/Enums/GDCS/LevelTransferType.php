<?php

namespace App\Enums\GDCS;

enum LevelTransferType: int
{
    case IN = 1;
    case OUT = 2;
    case OLD_GDCN = 3;
}
