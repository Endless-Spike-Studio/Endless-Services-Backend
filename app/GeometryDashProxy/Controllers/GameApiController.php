<?php

namespace App\GeometryDashProxy\Controllers;

use App\GeometryDashProxy\Exceptions\RequestException;
use App\GeometryDashProxy\Services\ProxyService;
use App\NewgroundsProxy\Exceptions\SongFetchException;
use App\NewgroundsProxy\Services\SongService;
use App\Shared\Controllers\Controller;
use Illuminate\Http\Request;

class GameApiController extends Controller
{
	public function __construct(
		protected readonly SongService  $song,
		protected readonly ProxyService $proxy
	)
	{

	}

	/**
	 * @throws SongFetchException
	 */
	public function getSong(Request $request): string
	{
		$id = $request->integer('songID');

		if (empty($id)) {
			abort(404);
		}

		return $this->song->get($id)->toObject();
	}

	/**
	 * @throws RequestException
	 */
	public function handle(Request $request): string
	{
		$uri = $request->getRequestUri();
		$data = $request->all();

		return $this->proxy->post($uri, $data);
	}
}