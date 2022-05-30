<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            if ($request->routeIs('gdcs.*')) {
                return route('gdcs.login');
            }

            return route('login');
        }

        abort(401);
    }
}
