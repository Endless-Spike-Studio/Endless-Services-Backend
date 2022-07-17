<?php

namespace App\Exceptions\NGProxy;

use Exception;

class SongNotFoundException extends Exception
{
    public function render(): void
    {
        abort(404);
    }
}
