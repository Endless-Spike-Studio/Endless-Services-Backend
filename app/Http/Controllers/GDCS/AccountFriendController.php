<?php

namespace App\Http\Controllers\GDCS;

use App\Enums\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\AccountFriendRemoveRequest;

class AccountFriendController extends Controller
{
    public function remove(AccountFriendRemoveRequest $request): int
    {
        $data = $request->validated();

        return $request->account
            ->friends()
            ->where('account_id', $data['targetAccountID'])
            ->orWhere('friend_account_id', $data['targetAccountID'])
            ->delete()
            ? Response::ACCOUNT_FRIEND_REMOVE_SUCCESS->value
            : \App\Enums\Response::ACCOUNT_FRIEND_REMOVE_FAILED->value;

    }
}
