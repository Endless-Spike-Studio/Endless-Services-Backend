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
	 * @throws RequestException
	 * @throws SongFetchException
	 */
	public function handle(Request $request): string
	{
		$uri = $request->getRequestUri();
		$data = $request->all();

		if ($uri === '/getGJSongInfo.php') {
			return $this->song->get($data['songID'])->toObject();
		}

		return $this->proxy->post($uri, $data);
	}
}