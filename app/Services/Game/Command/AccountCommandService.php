<?php

namespace App\Services\Game\Command;

class AccountCommandService extends BaseCommandService
{
    protected function test(): string
    {
        return __('gdcn.game.command.worked');
    }
}
