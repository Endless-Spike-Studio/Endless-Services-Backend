<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;

class ProjectController extends Controller
{
    public function update(): void
    {
        Cache::forget(
            config('vite.remote_manifest.cache_key', 'vite.remote_manifest')
        );

        exec('/vendor/bin/envoy run deploy-backend');
    }
}
