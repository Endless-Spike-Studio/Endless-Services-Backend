<?php

namespace App\Http\Controllers\GDCS;

use App\Enums\GDCS\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\AccountSettingUpdateRequest;

class AccountSettingController extends Controller
{
    public function update(AccountSettingUpdateRequest $request): int
    {
        $data = $request->validated();

        $setting = $request->account->setting;
        $setting->message_state = $data['mS'];
        $setting->friend_request_state = $data['frS'];
        $setting->comment_history_state = $data['cS'];
        $setting->youtube_channel = $data['yt'];
        $setting->twitter = $data['twitter'];
        $setting->twitch = $data['twitch'];
        $setting->save();

        return Response::ACCOUNT_SETTING_UPDATE_SUCCESS->value;
    }
}
