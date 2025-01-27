<?php

namespace App\EndlessProxy\Controllers;

use App\EndlessProxy\Services\GeometryDashCustomContentStorageService;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\StreamedResponse;

readonly class GameCustomContentProxyController
{
	public function __construct(
		protected GeometryDashCustomContentStorageService $storage
	)
	{

	}

	public function base(): string
	{
		return URL::action([__CLASS__, 'handle'], '/');
	}

	public function handle(string $path): StreamedResponse
	{
		return $this->storage->download($path);
	}
}