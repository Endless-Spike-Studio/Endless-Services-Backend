<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    public function share(Request $request): array
    {
        $account = Auth::guard('gdcs')->user();

        return array_merge(parent::share($request), [
            'messages' => $request->session()
                ->pull('messages', []),
            'gdcs' => [
                'account' => $account
            ]
        ]);
    }
}
