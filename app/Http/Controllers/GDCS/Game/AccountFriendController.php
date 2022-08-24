<?php

namespace App\Http\Controllers\GDCS\Game;

use App\Enums\Response;
use App\Exceptions\GeometryDashChineseServerException;
use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\Game\AccountFriendRemoveRequest;
use App\Http\Traits\GameLog;
use App\Models\GDCS\AccountFriend;

class AccountFriendController extends Controller
{
    use GameLog;

    /**
     * @throws GeometryDashChineseServerException
     */
    public function remove(AccountFriendRemoveRequest $request): int
    {
        $data = $request->validated();
        $query = AccountFriend::findBetween($request->account->id, $data['targetAccountID']);

        if (!$query->exists()) {
            throw new GeometryDashChineseServerException(__('gdcn.game.error.account_friend_delete_failed_target_not_found'), Response::GAME_ACCOUNT_FRIEND_DELETE_FAILED_NOT_FOUND->value);
        }

        $query->delete();
        $this->logGame(__('gdcn.game.action.account_friend_delete_success'));

        return Response::GAME_ACCOUNT_FRIEND_DELETE_SUCCESS->value;
    }
}
