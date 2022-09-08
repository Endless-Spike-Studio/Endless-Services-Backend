<?php

namespace App\Http\Middleware;

use App\Models\GDCS\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Inertia\Middleware;
use Innocenzi\Vite\Vite;

class HandleInertiaRequests extends Middleware
{
    public function share(Request $request): array
    {
        $data = [];

        $data['git_commit_hash'] = Cache::get('git_commit_hash', static function () {
            $hash = exec('git log --pretty="%h" -n1 HEAD');
            Cache::set('git_commit_hash', $hash);

            return $hash;
        });

        $data['versions'] = [
            'php' => PHP_VERSION,
            'laravel' => App::version()
        ];

        if (Route::is('gdcs.*')) {
            /** @var Account $account */
            $account = Auth::guard('gdcs')->user();

            if ($account !== null) {
                $data['GDCS'] = [
                    'account' => $account->only(['id', 'name']),
                    'user' => $account->load('user:id,uuid,name')->getRelationValue('user'),
                ];
            }
        }

        $data['messages'] = Session::pull('messages', []);
        $data['csrf_token'] = Session::token();

        return array_merge(parent::share($request), $data);
    }

    public function version(Request $request): ?string
    {
        return app(Vite::class)->getHash();
    }
}
