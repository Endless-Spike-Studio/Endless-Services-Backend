<?php

namespace App\Jobs\GDCS;

use App\Models\GDCS\Account;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendEmailVerification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected Account $account
    )
    {
    }

    public function handle(): void
    {
        $this->account->sendEmailVerificationNotification();
    }
}
