<?php

namespace App\Http\Controllers;

use Tightenco\Ziggy\Ziggy;

class ApiController
{
    public function routes(): Ziggy
    {
        return new Ziggy();
    }
}
