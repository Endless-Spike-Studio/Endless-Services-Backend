<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
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
                'account' => $account?->only(['id', 'name'])
            ],
            '754a08ddf8bcb1cf22f310f09206dd783d42f7dd' => [
                '47425e4490d1548713efea3b8a6f5d778e4b1766' => fn() => PHP_VERSION,
                '7b937a43bb67901cc0cb207e6bcf13a606611cf7' => fn() => App::version(),
                '6aef87d005f444c7023bc154db6ec02428cd43b7' => fn() => trim(
                    shell_exec('git log -1 --pretty=%h')
                )
            ]
        ]);
    }
}
