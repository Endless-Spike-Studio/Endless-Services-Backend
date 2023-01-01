<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Artisan;

class EnsureManifestSyncedListener
{
    public function handle(): void
    {
        Artisan::call('manifest:sync');
    }
}
