<?php

namespace App\EndlessServer\Controllers;

use App\EndlessServer\Models\Account;
use App\EndlessServer\Requests\GameAccountLoginRequest;
use App\EndlessServer\Requests\GameAccountRegisterRequest;
use App\EndlessServer\Services\GameAccountService;
use App\GeometryDash\Enums\GeometryDashResponses;
use Illuminate\Support\Facades\Hash;

readonly class GameAccountController
{
	public function __construct(
		protected GameAccountService $service
	)
	{

	}

	public function register(GameAccountRegisterRequest $request): string
	{
		$data = $request->validated();

		$account = $this->service->register($data['userName'], $data['email'], $data['password']);

		$this->service->storageGjp2($account, $data['password']);

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

		if (!Hash::check($data['password'], $account->password)) {
			return GeometryDashResponses::ACCOUNT_LOGIN_FAILED_WRONG_PASSWORD->value;
		}

		$this->service->storageGjp2($account, $data['password']);

		$player = $this->service->queryAccountPlayer($account, $data['udid']);

		return implode(',', [
			$account->id,
			$player->id
		]);
	}
}