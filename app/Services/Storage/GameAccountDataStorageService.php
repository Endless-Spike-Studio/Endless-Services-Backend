<?php

namespace App\Services\Storage;

use App\Services\StorageService;

class GameAccountDataStorageService extends StorageService
{
	public function __construct()
	{
		parent::__construct(
			config('gdcn.storages.game.accounts')
		);
	}
}
