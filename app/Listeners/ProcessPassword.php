<?php

namespace App\Listeners;

use App\Events\GDCS\AccountRegistered;
use App\Events\UserRegistered;
use Illuminate\Support\Facades\Hash;

class ProcessPassword
{
    public function handle(AccountRegistered|UserRegistered $event): void
    {
        $event->model->update([
            'password' => Hash::make($event->model->password)
        ]);
    }
}
