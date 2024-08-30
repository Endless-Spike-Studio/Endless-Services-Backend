<?php

namespace App\EndlessServer\Controllers;

use App\EndlessServer\Models\Account;
use App\EndlessServer\Requests\GameAccountLoginRequest;
use App\EndlessServer\Requests\GameAccountRegisterRequest;
use App\EndlessServer\Services\GameAccountService;
use App\GeometryDash\Enums\GeometryDashResponses;

class GameAccountController
{
	public function __construct(
		protected GameAccountService $service
	)
	{

	}

	public function register(GameAccountRegisterRequest $request): string
	{
		$data = $request->validated();

		$this->service->register($data['userName'], $data['email'], $data['password']);

		return GeometryDashResponses::ACCOUNT_REGISTER_SUCCESS->value;
	}

	public function login(GameAccountLoginRequest $request): string
	{
		$data = $request->validated();

		$account = Account::query()
			->where('name', $data['userName'])
			->first();

		if (empty($account)) {
			return GeometryDashResponses::ACCOUNT_LOGIN_FAILED_ACCOUNT_NOT_FOUND->value;
		}

		if (!$account->hasVerifiedEmail()) {
			return GeometryDashResponses::ACCOUNT_LOGIN_FAILED_EMAIL_NOT_VERIFIED->value;
		}

		$player = $this->service->queryAccountPlayer($account, $data['udid']);

		return implode(',', [
			$account->id,
			$player->id
		]);
	}
}