<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;

class ApiController extends Controller
{
    public function removeOutdatedManifestCache(): void
    {
        Cache::forget(
            config('vite.remote_manifest.cache_key', 'vite.remote_manifest')
        );
    }
}
