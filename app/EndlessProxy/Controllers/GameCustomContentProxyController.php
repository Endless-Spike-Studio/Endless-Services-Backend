<?php

namespace App\EndlessProxy\Controllers;

use App\EndlessProxy\Services\GeometryDashCustomContentStorageService;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GameCustomContentProxyController
{
	public function __construct(
		protected readonly GeometryDashCustomContentStorageService $storage
	)
	{

	}

	public function base(): string
	{
		return URL::action([__CLASS__, 'handle'], '/');
	}

	public function handle(string $path): StreamedResponse
	{
		$filename = basename($path);

		return Response::streamDownload(function () use ($path) {
			echo $this->storage->raw($path);
		}, $filename);
	}
}