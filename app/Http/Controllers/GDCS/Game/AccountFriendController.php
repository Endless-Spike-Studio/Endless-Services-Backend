<?php

namespace App\Http\Controllers\GDCS\Game;

use App\Enums\Response;
use App\Exceptions\GDCS\GameException;
use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\AccountFriendRemoveRequest;
use App\Models\GDCS\AccountFriend;

class AccountFriendController extends Controller
{
    /**
     * @throws GameException
     */
    public function remove(AccountFriendRemoveRequest $request): int
    {
        $data = $request->validated();
        $query = AccountFriend::findBetween($request->account->id, $data['targetAccountID']);

        if (!$query->exists()) {
            throw new GameException(__('error.game.account.friend.not_found'), log_context: [
                'account_id' => $request->account->id,
                'target_account_id' => $data['targetAccountID']
            ], response_code: Response::GAME_ACCOUNT_FRIEND_DELETE_FAILED_NOT_FOUND->value);
        }

        $query->delete();
        return Response::GAME_ACCOUNT_FRIEND_DELETE_SUCCESS->value;
    }
}
