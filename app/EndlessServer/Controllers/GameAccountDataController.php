<?php

namespace App\EndlessServer\Controllers;

use App\EndlessServer\Enums\EndlessServerAuthenticationGuards;
use App\EndlessServer\Models\Account;
use App\EndlessServer\Requests\GameAccountDataLoadRequest;
use App\EndlessServer\Requests\GameAccountDataSaveRequest;
use App\EndlessServer\Requests\GameFetchAccountDataBaseUrlRequest;
use App\EndlessServer\Services\GameAccountDataStorageService;
use App\EndlessServer\Services\GameAccountService;
use App\EndlessServer\Services\GamePlayerDataService;
use App\EndlessServer\Services\GamePlayerStatisticService;
use App\GeometryDash\Enums\GeometryDashResponses;
use Illuminate\Support\Facades\Auth;

readonly class GameAccountDataController
{
	public function __construct(
		protected GameAccountService            $accountService,
		protected GamePlayerDataService         $playerDataService,
		protected GamePlayerStatisticService    $playerStatisticService,
		protected GameAccountDataStorageService $storageService
	)
	{

	}

	public function baseUrl(GameFetchAccountDataBaseUrlRequest $request): string
	{
		$request->validated();

		return url('/api/EndlessServer/GeometryDash');
	}

	public function save(GameAccountDataSaveRequest $request): int
	{
		$data = $request->validated();

		/* @var Account $account */
		$account = Auth::guard(EndlessServerAuthenticationGuards::ACCOUNT->value)->user();

		$account->player->data->update([
			'game_version' => $data['gameVersion'],
			'binary_version' => $data['binaryVersion']
		]);

		$this->storageService->account = $account;

		$this->storageService->store($data['saveData']);

		return GeometryDashResponses::ACCOUNT_DATA_SAVE_SUCCESS->value;
	}

	public function load(GameAccountDataLoadRequest $request): string
	{
		$data = $request->validated();

		/* @var Account $account */
		$account = Auth::guard(EndlessServerAuthenticationGuards::ACCOUNT->value)->user();

		$this->storageService->account = $account;

		$saveData = $this->storageService->fetch();

		return implode(';', [
			$saveData,
			$account->player->data->game_version,
			$account->player->data->binary_version,
			'?',
			'?'
		]);
	}
}