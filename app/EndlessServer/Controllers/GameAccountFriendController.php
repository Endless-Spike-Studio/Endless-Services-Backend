<?php

namespace App\EndlessServer\Controllers;

use App\EndlessServer\Enums\EndlessServerAuthenticationGuards;
use App\EndlessServer\Models\Account;
use App\EndlessServer\Models\AccountFriend;
use App\EndlessServer\Requests\GameAccountFriendDeleteRequest;
use App\GeometryDash\Enums\GeometryDashResponses;
use Illuminate\Support\Facades\Auth;

readonly class GameAccountFriendController
{
	public function delete(GameAccountFriendDeleteRequest $request): int
	{
		$data = $request->validated();

		/** @var Account $account */
		$account = Auth::guard(EndlessServerAuthenticationGuards::ACCOUNT->value)->user();

		$targetAccount = Account::query()
			->where('id', $data['targetAccountID'])
			->first();

		AccountFriend::query()
			->where('account_id', $account->id)
			->where('target_account_id', $targetAccount->id)
			->delete();

		AccountFriend::query()
			->where('target_account_id', $account->id)
			->where('account_id', $targetAccount->id)
			->delete();

		return GeometryDashResponses::ACCOUNT_FRIEND_DELETE->value;
	}
}