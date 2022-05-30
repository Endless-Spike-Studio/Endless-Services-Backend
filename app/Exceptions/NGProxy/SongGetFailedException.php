<?php

namespace App\Exceptions\NGProxy;

use Exception;

class SongGetFailedException extends Exception
{
    public function render(): void
    {
        abort(404);
    }
}
