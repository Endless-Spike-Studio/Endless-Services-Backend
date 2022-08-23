<?php

namespace App\Http\Controllers\GDCS\Game;

use App\Enums\Response;
use App\Exceptions\GDCS\GameException;
use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\AccountFriendRemoveRequest;
use App\Http\Traits\GameLog;
use App\Models\GDCS\AccountFriend;

class AccountFriendController extends Controller
{
    use GameLog;

    /**
     * @throws GameException
     */
    public function remove(AccountFriendRemoveRequest $request): int
    {
        $data = $request->validated();
        $query = AccountFriend::findBetween($request->account->id, $data['targetAccountID']);

        if (!$query->exists()) {
            throw new GameException(__('gdcn.game.error.account_friend_delete_failed_target_not_found'), response_code: Response::GAME_ACCOUNT_FRIEND_DELETE_FAILED_NOT_FOUND->value);
        }

        $query->delete();
        $this->logGame(__('gdcn.game.action.account_friend_delete_success'));

        return Response::GAME_ACCOUNT_FRIEND_DELETE_SUCCESS->value;
    }
}
