<?php

namespace App\EndlessServer\Controllers;

use App\EndlessServer\Enums\EndlessServerAuthenticationGuards;
use App\EndlessServer\Models\Account;
use App\EndlessServer\Requests\GameAccountBlocklistAddRequest;
use App\EndlessServer\Requests\GameAccountBlocklistDeleteRequest;
use App\GeometryDash\Enums\GeometryDashResponses;
use Illuminate\Support\Facades\Auth;

readonly class GameAccountBlocklistController
{
	public function add(GameAccountBlocklistAddRequest $request): int
	{
		$data = $request->validated();

		/** @var Account $account */
		$account = Auth::guard(EndlessServerAuthenticationGuards::ACCOUNT->value)->user();

		$targetAccount = Account::query()
			->where('id', $data['targetAccountID'])
			->first();

		$account->blocklist()
			->create([
				'target_account_id' => $data['targetAccountID']
			]);

		$targetAccount->messages()
			->where('target_account_id', $account->id)
			->delete();

		return GeometryDashResponses::ACCOUNT_BLOCKLIST_ADD_SUCCESS->value;
	}

	public function delete(GameAccountBlocklistDeleteRequest $request): int
	{
		$data = $request->validated();

		/** @var Account $account */
		$account = Auth::guard(EndlessServerAuthenticationGuards::ACCOUNT->value)->user();

		$account->blocklist()
			->where('target_account_id', $data['targetAccountID'])
			->delete();

		return GeometryDashResponses::ACCOUNT_BLOCKLIST_DELETE_SUCCESS->value;
	}
}