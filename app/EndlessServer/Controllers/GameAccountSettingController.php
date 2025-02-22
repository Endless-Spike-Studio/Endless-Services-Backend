<?php

namespace App\EndlessServer\Controllers;

use App\EndlessServer\Enums\EndlessServerAuthenticationGuards;
use App\EndlessServer\Models\Account;
use App\EndlessServer\Requests\GameAccountSettingUpdateRequest;
use App\EndlessServer\Services\GameAccountSettingService;
use App\GeometryDash\Enums\GeometryDashAccountSettingCommentHistoryStates;
use App\GeometryDash\Enums\GeometryDashAccountSettingFriendRequestStates;
use App\GeometryDash\Enums\GeometryDashAccountSettingMessageStates;
use App\GeometryDash\Enums\GeometryDashResponses;
use Illuminate\Support\Facades\Auth;

readonly class GameAccountSettingController
{
	public function __construct(
		public GameAccountSettingService $service
	)
	{

	}

	public function update(GameAccountSettingUpdateRequest $request): int
	{
		$data = $request->validated();

		/** @var Account $account */
		$account = Auth::guard(EndlessServerAuthenticationGuards::ACCOUNT->value)->user();

		if ($data['yt'] === null) {
			$data['yt'] = '';
		}

		if ($data['twitter'] === null) {
			$data['twitter'] = '';
		}

		if ($data['twitch'] === null) {
			$data['twitch'] = '';
		}

		$this->service->update($account->id, GeometryDashAccountSettingMessageStates::from($data['mS']), GeometryDashAccountSettingFriendRequestStates::from($data['frS']), GeometryDashAccountSettingCommentHistoryStates::from($data['cS']), $data['yt'], $data['twitter'], $data['twitch']);

		return GeometryDashResponses::ACCOUNT_SETTING_UPDATE_SUCCESS->value;
	}
}