<?php

namespace App\EndlessServer\Controllers;

use App\EndlessServer\Enums\EndlessServerAuthenticationGuards;
use App\EndlessServer\Models\Account;
use App\EndlessServer\Requests\GameAccountSettingUpdateRequest;
use App\EndlessServer\Services\GameAccountSettingService;
use App\GeometryDash\Enums\GeometryDashAccountSettingCommentHistoryState;
use App\GeometryDash\Enums\GeometryDashAccountSettingFriendRequestState;
use App\GeometryDash\Enums\GeometryDashAccountSettingMessageState;
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

		$this->service->initialize($account->id);

		if (is_null($data['yt'])) {
			$data['yt'] = '';
		}

		if (is_null($data['twitter'])) {
			$data['twitter'] = '';
		}

		if (is_null($data['twitch'])) {
			$data['twitch'] = '';
		}

		$this->service->update($account->id, GeometryDashAccountSettingMessageState::from($data['mS']), GeometryDashAccountSettingFriendRequestState::from($data['frS']), GeometryDashAccountSettingCommentHistoryState::from($data['cS']), $data['yt'], $data['twitter'], $data['twitch']);

		return GeometryDashResponses::ACCOUNT_SETTING_UPDATE_SUCCESS->value;
	}
}