<?php

namespace App\EndlessServer\Services;

use App\EndlessServer\Models\Account;
use App\EndlessServer\Models\Player;
use App\GeometryDash\Services\GeometryDashAlgorithmService;
use Illuminate\Support\Str;

readonly class GameAccountService
{
	public function __construct(
		protected GeometryDashAlgorithmService $algorithmService
	)
	{

	}

	public function queryAccountPlayer(Account $account, ?string $udid = null)
	{
		if ($udid === null) {
			$udid = Str::uuid()
				->toString();
		}

		$player = Player::query()
			->where('uuid', $account->id)
			->first();

		if ($player === null) {
			return Player::query()
				->create([
					'uuid' => $account->id,
					'name' => $account->name,
					'udid' => $udid
				]);
		}

		return $player;
	}

	public function storageGjp2(Account $account, string $password): void
	{
		$account->gjp2()
			->updateOrCreate([
				'gjp2' => $this->algorithmService->generateGjp2($password)
			]);
	}
}