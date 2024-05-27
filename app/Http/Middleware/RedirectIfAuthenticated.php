<?php

namespace App\Http\Middleware;

use App\Http\Traits\HasMessage;
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
				if ($request->inertia()) {
					$this->pushErrorMessage(__('gdcn.web.error.already_logged'));
				}

				return match ($guard) {
					'gdcs' => to_route('gdcs.home'),
					default => abort(403)
				};
			}
		}

		return $next($request);
	}
}
