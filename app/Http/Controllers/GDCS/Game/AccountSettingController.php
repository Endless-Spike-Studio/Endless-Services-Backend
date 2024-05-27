<?php

namespace App\Http\Controllers\GDCS\Game;

use App\Enums\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\Game\AccountSettingUpdateRequest;
use App\Http\Traits\GameLog;

class AccountSettingController extends Controller
{
	use GameLog;

	public function update(AccountSettingUpdateRequest $request): int
	{
		$data = $request->validated();

		$request->account->setting->update([
			'message_state' => $data['mS'],
			'friend_request_state' => $data['frS'],
			'comment_history_state' => $data['cS'],
			'youtube_channel' => $data['yt'],
			'twitter' => $data['twitter'],
			'twitch' => $data['twitch'],
		]);

		$this->logGame(__('gdcn.game.action.account_setting_update_success'));
		return Response::GAME_ACCOUNT_SETTING_UPDATE_SUCCESS->value;
	}
}
