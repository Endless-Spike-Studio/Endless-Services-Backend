<?php

namespace App\EndlessServer\Controllers;

use App\EndlessProxy\Services\GeometryDashCustomContentStorageService;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\StreamedResponse;

readonly class GameCustomContentController
{
	public function __construct(
		protected GeometryDashCustomContentStorageService $customContentStorage
	)
	{

	}

	public function getURL(): string
	{
		return URL::action([__CLASS__, 'handle'], '/');
	}

	public function handle(string $path): StreamedResponse
	{
		$this->customContentStorage->path = $path;

		return $this->customContentStorage->download();
	}
}