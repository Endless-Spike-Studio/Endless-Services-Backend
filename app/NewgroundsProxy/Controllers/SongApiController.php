<?php

namespace App\NewgroundsProxy\Controllers;

use App\Exceptions\StorageException;
use App\Http\Controllers\Controller;
use App\NewgroundsProxy\Services\SongService;
use App\NewgroundsProxy\Services\SongStorageService;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SongApiController extends Controller
{
	public function __construct(
		protected SongService        $service,
		protected SongStorageService $storage
	)
	{

	}

	public function info(int $id): array
	{
		return $this->service->get($id)->toArray();
	}

	public function object(int $id): string
	{
		return $this->service->get($id)->toObject();
	}

	/**
	 * @throws StorageException
	 */
	public function download(int $id): StreamedResponse
	{
		return $this->storage->download([
			'id' => $id
		]);
	}
}