<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class Authenticate extends Middleware
{
    protected function redirectTo($request): string
    {
        Session::put([
            'back_url' => URL::current()
        ]);

        return match (true) {
            Route::is('gdcs.*') => route('gdcs.auth.login'),
            default => abort(401)
        };
    }
}
