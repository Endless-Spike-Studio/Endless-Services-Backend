<?php

namespace App\EndlessProxy\Services;

class GeometryDashCustomContentProxyService
{
	public function __construct(
		protected readonly GeometryDashCuistomContentStorageService $storage
	)
	{

	}

	public function url(string $path): string
	{
		return $this->storage->url($path);
	}
}