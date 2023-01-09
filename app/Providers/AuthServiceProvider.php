<?php

namespace App\Providers;

use App\Models\GDCS\Contest;
use App\Models\GDCS\Level;
use App\Policies\GDCS\ContestPolicy;
use App\Policies\GDCS\LevelPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Level::class => LevelPolicy::class,
        Contest::class => ContestPolicy::class
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
