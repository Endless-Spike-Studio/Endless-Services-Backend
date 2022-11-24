<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Artisan;

class EnsureSyncManifestListener
{
    public function handle()
    {
        Artisan::call('manifest:sync');
    }
}
