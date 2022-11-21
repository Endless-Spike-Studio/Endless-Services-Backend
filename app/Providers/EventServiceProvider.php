<?php

namespace App\Providers;

use App\Listeners\LevelEventSubscriber;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $subscribe = [
        LevelEventSubscriber::class
    ];
}
