<?php

namespace App\Services\Storage;

class CustomSongStorageService extends BaseStorageService
{
	public function __construct()
	{
		parent::__construct(
			config('gdcn.storages.customSongs')
		);
	}
}
