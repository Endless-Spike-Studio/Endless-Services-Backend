<?php

namespace App\Providers;

use App\Models\GDCS\AccountComment;
use App\Policies\GDCS\AccountCommentPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        AccountComment::class => AccountCommentPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
