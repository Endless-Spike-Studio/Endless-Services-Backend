<?php

namespace App\EndlessProxy\Events;

use App\Api\Requests\ApiRequest;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Client\Response;

readonly class EndlessProxyRequestHandledEvent
{
	use Dispatchable;

	public function __construct(
		public string     $path,
		public ApiRequest $request,
		public Response   $response
	)
	{

	}
}