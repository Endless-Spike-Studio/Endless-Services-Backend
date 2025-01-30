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
use App\GeometryDash\Enums\GeometryDashResponses;
use Illuminate\Support\Facades\Auth;

readonly class GameAccountDataController
{
	public function __construct(
		protected GameAccountService            $accountService,
		protected GamePlayerDataService         $playerDataService,
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

		$player = $this->accountService->queryAccountPlayer($account);

		$this->playerDataService->initialize($player->id);

		$this->playerDataService->updateVersions($player->id, $data['gameVersion'], $data['binaryVersion']);

		$this->storageService->account = $account;

		$this->storageService->store($data['saveData']);

		return GeometryDashResponses::ACCOUNT_DATA_SAVE_SUCCESS->value;
	}

	public function load(GameAccountDataLoadRequest $request): string
	{
		$data = $request->validated();

		/* @var Account $account */
		$account = Auth::guard(EndlessServerAuthenticationGuards::ACCOUNT->value)->user();

		$player = $this->accountService->queryAccountPlayer($account);

		$this->playerDataService->initialize($player->id);

		$this->storageService->account = $account;

		$saveData = $this->storageService->fetch();

		return implode(';', [
			$saveData,
			$player->data->game_version,
			$player->data->binary_version,
			'?',
			'?'
		]);
	}
}