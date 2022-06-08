<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    public function share(Request $request): array
    {
        $hash = Cache::get('git_commit_hash', static function () {
            return exec('git log --pretty="%h" -n1 HEAD');
        });

        Cache::set('git_commit_hash', $hash);
        return array_merge(parent::share($request), [
            'user' => $request->user(),
            'gdcs' => [
                'account' => $request->user('gdcs')
                    ?->load('user')
            ],
            'messages' => Session::pull('messages', []),
            'versions' => [
                'php' => PHP_VERSION,
                'laravel' => App::version(),
                'git' => $hash
            ]
        ]);
    }

    public function version(Request $request): ?string
    {
        return vite()->getHash();
    }
}
