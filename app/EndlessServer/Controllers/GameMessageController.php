<?php

namespace App\EndlessServer\Controllers;

use App\EndlessServer\Enums\EndlessServerAuthenticationGuards;
use App\EndlessServer\Models\Account;
use App\EndlessServer\Requests\GameMessageSendRequest;
use App\GeometryDash\Enums\GeometryDashResponses;
use Base64Url\Base64Url;
use Illuminate\Support\Facades\Auth;

readonly class GameMessageController
{
	public function send(GameMessageSendRequest $request): int
	{
		$data = $request->validated();

		/** @var Account $account */
		$account = Auth::guard(EndlessServerAuthenticationGuards::ACCOUNT->value)->user();

		$account->messages()
			->create([
				'target_account_id' => $data['toAccountIDD'],
				'subject' => Base64Url::decode($data['subject']),
				'body' => Base64Url::decode($data['body']),
				'readed' => false
			]);

		return GeometryDashResponses::ACCOUNT_MESSAGE_SEND_SUCCESS->value;
	}
}