<?php

namespace App\Services\Storage;

class GameLevelDataStorageService extends BaseStorageService
{
	public function __construct()
	{
		parent::__construct(
			config('gdcn.storages.game.levels')
		);
	}
}
