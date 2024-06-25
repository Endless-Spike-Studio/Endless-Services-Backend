<?php

namespace App\EndlessProxy\Controllers;

use App\EndlessProxy\Services\GeometryDashProxyService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;

class GameApiProxyController
{
	public function __construct(
		protected readonly GeometryDashProxyService $service
	)
	{

	}

	/**
	 * @throws ConnectionException
	 */
	public function handle(Request $request, string $path): string
	{
		$data = $request->all();

		return $this->service
			->getRequest()
			->post($path, $data);
	}
}