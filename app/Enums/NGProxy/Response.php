<?php

namespace App\Enums\NGProxy;

enum Response: int
{
    case SONG_NOT_FOUND = -1;
    case SONG_DISABLED = -2;
}
