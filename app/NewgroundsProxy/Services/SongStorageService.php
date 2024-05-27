<?php

namespace App\NewgroundsProxy\Services;

use App\Services\Storage\BaseStorageService;

class SongStorageService extends BaseStorageService
{
	public function __construct()
	{
		parent::__construct(
			config('gdcn.storages.songs')
		);
	}
}