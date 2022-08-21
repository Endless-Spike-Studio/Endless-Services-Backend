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
    public function add(AccountBlockRequest $request): int
    {
        $data = $request->validated();

        $context = [
            'account_id' => $data['accountID'],
            'target_account_id' => $data['targetAccountID']
        ];

        if (AccountBlock::where($context)->exists()) {
            throw new GameException(__('error.game.account.block.already_exists'), log_context: $context, response_code: Response::GAME_ACCOUNT_BLOCK_FAILED_ALREADY_EXISTS->value);
        }

        AccountBlock::create($context);
        $this->logGame(__('messages.game.account.block.added'), $context);

        return Response::GAME_ACCOUNT_BLOCK_SUCCESS->value;
    }

    /**
     * @throws GameException
     */
    public function remove(AccountUnblockRequest $request): int
    {
        $data = $request->validated();

        $context = [
            'account_id' => $data['accountID'],
            'target_account_id' => $data['targetAccountID']
        ];

        $query = AccountBlock::where($context);
        if (!$query->exists()) {
            throw new GameException(__('error.game.account.block.not_found'), log_context: $context, response_code: Response::GAME_ACCOUNT_UNBLOCK_FAILED_NOT_FOUND->value);
        }

        $query->delete();
        $this->logGame(__('messages.game.account.block.removed'), $context);

        return Response::GAME_ACCOUNT_UNBLOCK_SUCCESS->value;
    }
}
