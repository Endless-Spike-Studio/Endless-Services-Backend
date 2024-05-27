<?php

namespace App\NewgroundsProxy\Services;

use App\Services\StorageService;

class SongStorageService extends StorageService
{
	public function __construct()
	{
		parent::__construct(
			config('gdcn.storages.songs')
		);
	}
}