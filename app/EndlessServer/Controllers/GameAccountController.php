<?php

namespace App\EndlessServer\Controllers;

use App\EndlessServer\Enums\EndlessServerAuthenticationGuards;
use App\EndlessServer\Models\Account;
use App\EndlessServer\Requests\GameAccountLoginRequest;
use App\EndlessServer\Requests\GameAccountRegisterRequest;
use App\EndlessServer\Requests\GameRequestAccountAccessRequest;
use App\EndlessServer\Services\GameAccountService;
use App\GeometryDash\Enums\GeometryDashResponses;
use Illuminate\Support\Facades\Auth;

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

		$account = Account::query()
			->create([
				'name' => $data['userName'],
				'email' => $data['email'],
				'password' => $data['password']
			]);

		$account->sendEmailVerificationNotification();

		$this->service->storageGjp2($account, $data['password']);

		return GeometryDashResponses::ACCOUNT_REGISTER_SUCCESS->value;
	}

	public function login(GameAccountLoginRequest $request): string
	{
		$data = $request->validated();

		$account = Account::query()
			->where('name', $data['userName'])
			->first();

		if ($account === null) {
			return GeometryDashResponses::ACCOUNT_LOGIN_FAILED_ACCOUNT_NOT_FOUND->value;
		}

		if (!$account->hasVerifiedEmail()) {
			return GeometryDashResponses::ACCOUNT_LOGIN_FAILED_EMAIL_NOT_VERIFIED->value;
		}

		if ($data['gjp2'] !== $account->gjp2->value) {
			return GeometryDashResponses::ACCOUNT_LOGIN_FAILED_WRONG_PASSWORD->value;
		}

		$player = $this->service->queryAccountPlayer($account, $data['udid']);

		return implode(',', [
			$account->id,
			$player->id
		]);
	}

	public function requestAccess(GameRequestAccountAccessRequest $request)
	{
		$request->validated();

		/** @var Account $account */
		$account = Auth::guard(EndlessServerAuthenticationGuards::ACCOUNT->value)->user();

		$account->load('roles');

		if ($account->roles->isEmpty()) {
			return GeometryDashResponses::REQUEST_ACCOUNT_ACCESS_FAILED_NO_ROLES->value;
		}

		$level = $account->roles->max(fn($role) => $role->mod_level);

		if ($level <= 0) {
			return GeometryDashResponses::REQUEST_ACCOUNT_ACCESS_FAILED_NO_MOD_ROLES->value;
		}

		return $level;
	}
}