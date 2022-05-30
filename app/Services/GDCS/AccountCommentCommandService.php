<?php

namespace App\Services\GDCS;

class AccountCommentCommandService extends CommandService
{
    public function test(): string
    {
        return __('messages.command.it_worked');
    }
}
