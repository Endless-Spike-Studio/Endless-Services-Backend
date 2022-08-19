<?php

namespace App\Services\GDCS\Game;

use App\Enums\Response;
use App\Exceptions\GDCS\GameException;
use App\Models\GDCS\AccountBlock;
use Illuminate\Support\Facades\Log;

class AccountBlockService
{
    /**
     * @throws GameException
     */
    public function store(int $accountID, int $targetAccountID): void
    {
        $data = ['account_id' => $accountID, 'target_account_id' => $targetAccountID];

        if (AccountBlock::where($data)->exists()) {
            throw new GameException(__('error.game.account.block.already_exists'), log_context: $data, response_code: Response::GAME_ACCOUNT_BLOCK_FAILED_NOT_FOUND->value);
        }

        AccountBlock::create($data);
        Log::channel('gdcn')->info(__('messages.game.account.block_success'), $data);
    }

    /**
     * @throws GameException
     */
    public function destroy(int $accountID, int $targetAccountID): void
    {
        $data = ['account_id' => $accountID, 'target_account_id' => $targetAccountID];

        if (AccountBlock::where($data)->exists()) {
            throw new GameException(__('error.game.account.block.not_found'), log_context: $data, response_code: Response::GAME_ACCOUNT_UNBLOCK_FAILED_NOT_FOUND->value);
        }

        AccountBlock::create($data);
        Log::channel('gdcn')->info(__('messages.game.account.block.destroy'), $data);
    }
}
