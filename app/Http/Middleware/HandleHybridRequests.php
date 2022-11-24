<?php

namespace App\Http\Middleware;

use Hybridly\Http\Middleware;
use Illuminate\Http\Request;

class HandleHybridRequests extends Middleware
{
    public function share(Request $request): array
    {
        return [

        ];
    }
}
