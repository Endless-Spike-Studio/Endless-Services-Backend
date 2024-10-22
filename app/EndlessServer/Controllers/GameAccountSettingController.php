<?php

namespace App\EndlessServer\Controllers;

use App\EndlessServer\Enums\EndlessServerAuthenticationGuards;
use App\EndlessServer\Models\Account;
use App\EndlessServer\Requests\GameAccountSettingUpdateRequest;
use App\GeometryDash\Enums\GeometryDashResponses;
use Illuminate\Support\Facades\Auth;

class GameAccountSettingController
{
	public function update(GameAccountSettingUpdateRequest $request): int
	{
		$data = $request->validated();

		/** @var Account $account */
		$account = Auth::guard(EndlessServerAuthenticationGuards::ACCOUNT->value)->user();

		$account->setting()
			->updateOrCreate([
				'message_state' => $data['mS'],
				'friend_request_state' => $data['frS'],
				'comment_history_state' => $data['cS'],
				'youtube' => $data['yt'],
				'twitter' => $data['twitter'],
				'twitch' => $data['twitch']
			]);

		return GeometryDashResponses::ACCOUNT_SETTING_UPDATE_SUCCESS->value;
	}
}
