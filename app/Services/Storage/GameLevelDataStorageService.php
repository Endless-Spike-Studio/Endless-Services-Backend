<?php

namespace App\Services\Storage;

use App\Services\StorageService;

class GameLevelDataStorageService extends StorageService
{
	public function __construct()
	{
		parent::__construct(
			config('gdcn.storages.game.levels')
		);
	}
}
