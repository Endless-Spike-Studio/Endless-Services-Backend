<?php

namespace App\Providers;

use App\Events\GDCS\AccountEmailChanged;
use App\Events\GDCS\AccountPasswordChanged;
use App\Events\GDCS\AccountRegistered;
use App\Events\UserEmailChanged;
use App\Events\UserPasswordChanged;
use App\Events\UserRegistered;
use App\Listeners\ProcessPassword;
use App\Listeners\SendVerificationEmail;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        AccountRegistered::class => [
            ProcessPassword::class,
            SendVerificationEmail::class,
        ],
        AccountPasswordChanged::class => [
            ProcessPassword::class,
        ],
        AccountEmailChanged::class => [
            SendVerificationEmail::class,
        ],
        UserRegistered::class => [
            ProcessPassword::class,
            SendVerificationEmail::class,
        ],
        UserPasswordChanged::class => [
            ProcessPassword::class,
        ],
        UserEmailChanged::class => [
            SendVerificationEmail::class,
        ],
    ];
}
