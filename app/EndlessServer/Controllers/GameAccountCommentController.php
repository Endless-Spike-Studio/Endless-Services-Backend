<?php

namespace App\EndlessServer\Controllers;

use App\EndlessServer\Enums\EndlessServerAuthenticationGuards;
use App\EndlessServer\Models\Account;
use App\EndlessServer\Requests\GameAccountCommentUploadRequest;
use App\GeometryDash\Enums\GeometryDashResponses;
use Base64Url\Base64Url;
use Illuminate\Support\Facades\Auth;

class GameAccountCommentController
{
	public function upload(GameAccountCommentUploadRequest $request): int
	{
		$data = $request->validated();

		/** @var Account $account */
		$account = Auth::guard(EndlessServerAuthenticationGuards::ACCOUNT->value)->user();

		$account->comments()
			->create([
				'comment' => Base64Url::decode($data['comment'])
			]);

		return GeometryDashResponses::ACCOUNT_COMMENT_UPLOAD_SUCCESS->value;
	}
}
