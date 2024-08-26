<?php

namespace App\EndlessProxy\Controllers;

use App\EndlessProxy\Services\GeometryDashCustomContentProxyService;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;

class GameCustomContentProxyController
{
	public function __construct(
		protected readonly GeometryDashCustomContentProxyService $service
	)
	{

	}

	public function base(): string
	{
		return URL::action([__CLASS__, 'handle'], '/');
	}

	public function handle(string $path): string
	{
		$filename = basename($path);

		return Response::streamDownload(function () use ($path) {
			echo $this->service->raw($path);
		}, $filename);
	}
}