<?php

namespace App\Http\Middleware;

use App\Http\Traits\HasMessage;
use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    use HasMessage;

    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $this->message(__('messages.already_logged'), ['type' => 'error']);
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
