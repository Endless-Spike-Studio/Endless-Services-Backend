<?php

namespace App\NewgroundsProxy\Services;

use App\Storage\Services\StorageService;

class SongStorageService extends StorageService
{
	public function __construct()
	{
		parent::__construct(
			config('gdcn.ngproxy.storages.songs')
		);
	}
}