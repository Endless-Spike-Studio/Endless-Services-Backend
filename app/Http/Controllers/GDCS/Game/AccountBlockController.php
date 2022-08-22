<?php

namespace App\Http\Controllers\GDCS\Game;

use App\Enums\Response;
use App\Exceptions\GDCS\GameException;
use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\AccountBlockRequest;
use App\Http\Requests\GDCS\AccountUnblockRequest;
use App\Http\Traits\GameLog;
use App\Models\GDCS\AccountBlock;

class AccountBlockController extends Controller
{
    use GameLog;

    /**
     * @throws GameException
     */
    public function block(AccountBlockRequest $request): int
    {
        $data = $request->validated();

        $context = [
            'account_id' => $data['accountID'],
            'target_account_id' => $data['targetAccountID']
        ];

        if (AccountBlock::where($context)->exists()) {
            throw new GameException(__('error.game.account.block.already_exists'), response_code: Response::GAME_ACCOUNT_BLOCK_FAILED_ALREADY_EXISTS->value);
        }

        AccountBlock::create($context);
        $this->logGame(__('messages.game.account_block'));

        return Response::GAME_ACCOUNT_BLOCK_SUCCESS->value;
    }

    /**
     * @throws GameException
     */
    public function unblock(AccountUnblockRequest $request): int
    {
        $data = $request->validated();

        $context = [
            'account_id' => $data['accountID'],
            'target_account_id' => $data['targetAccountID']
        ];

        $query = AccountBlock::where($context);
        if (!$query->exists()) {
            throw new GameException(__('error.game.account.block.not_found'), response_code: Response::GAME_ACCOUNT_UNBLOCK_FAILED_NOT_FOUND->value);
        }

        $query->delete();
        $this->logGame(__('messages.game.account_unblock'));

        return Response::GAME_ACCOUNT_UNBLOCK_SUCCESS->value;
    }
}
