<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Tightenco\Ziggy\Ziggy;

class RouteController extends Controller
{
    public function ziggy()
    {
        if (Request::header('X-Auth') === '3a8fac65d497fd23b4038df87e5a597670d287cd') {
            return (new Ziggy)->toArray();
        }

        return Redirect::to('/');
    }
}
