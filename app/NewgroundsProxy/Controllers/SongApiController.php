<?php

namespace App\NewgroundsProxy\Controllers;

use App\NewgroundsProxy\Exceptions\SongFetchException;
use App\NewgroundsProxy\Services\SongService;
use App\Shared\Controllers\Controller;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SongApiController extends Controller
{
	public function __construct(
		protected readonly SongService $service
	)
	{

	}

	/**
	 * @throws SongFetchException
	 */
	public function info(int $id): array
	{
		return $this->service->get($id)->toArray();
	}

	/**
	 * @throws SongFetchException
	 */
	public function object(int $id): string
	{
		return $this->service->get($id)->toObject();
	}

	/**
	 * @throws SongFetchException
	 */
	public function download(int $id): StreamedResponse
	{
		return $this->service->download($id);
	}
}