<?php

namespace App\Services\Storage;

use App\Services\StorageService;

class CustomSongStorageService extends StorageService
{
	public function __construct()
	{
		parent::__construct(
			config('gdcn.storages.customSongs')
		);
	}
}
