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
            throw new GameException(__('gdcn.game.error.account_block_failed_already_exists'), response_code: Response::GAME_ACCOUNT_BLOCK_FAILED_ALREADY_EXISTS->value);
        }

        AccountBlock::create($context);
        $this->logGame(__('gdcn.game.action.account_block_success'));

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
            throw new GameException(__('gdcn.game.error.account_unblock_failed_not_found'), response_code: Response::GAME_ACCOUNT_UNBLOCK_FAILED_NOT_FOUND->value);
        }

        $query->delete();
        $this->logGame(__('gdcn.game.action.account_unblock_success'));

        return Response::GAME_ACCOUNT_UNBLOCK_SUCCESS->value;
    }
}
