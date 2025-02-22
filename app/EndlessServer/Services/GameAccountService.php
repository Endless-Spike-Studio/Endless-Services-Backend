<?php

namespace App\EndlessServer\Services;

use App\EndlessServer\Models\Account;
use App\GeometryDash\Services\GeometryDashAlgorithmService;

readonly class GameAccountService
{
	public function __construct(
		protected GeometryDashAlgorithmService $algorithmService
	)
	{

	}

	public function storageGjp2(Account $account, string $password): void
	{
		$account->gjp2()
			->updateOrCreate([
				'gjp2' => $this->algorithmService->generateGjp2($password)
			]);
	}
}