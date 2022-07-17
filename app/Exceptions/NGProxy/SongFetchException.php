<?php

namespace App\Exceptions\NGProxy;

use Exception;

class SongFetchException extends Exception
{
    public function render(): void
    {
        abort(404);
    }
}
