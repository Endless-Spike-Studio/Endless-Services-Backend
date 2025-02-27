<?php

namespace App\Base\Controllers;

use App\Common\Responses\SuccessResponse;
use Illuminate\Broadcasting\BroadcastController;
use Illuminate\Support\Facades\URL;

readonly class WebsocketController
{
	public function getInfo(): SuccessResponse
	{
		return new SuccessResponse([
			'broadcaster' => 'reverb',
			'key' => config('broadcasting.connections.reverb.key'),
			'wsHost' => config('websocket.host'),
			'wsPath' => config('websocket.path'),
			'wsPort' => config('websocket.ws_port'),
			'wssPort' => config('websocket.wss_port'),
			'forceTLS' => false,
			'enabledTransports' => ['ws', 'wss'],
			'authEndpoint' => URL::action([BroadcastController::class, 'authenticate'])
		]);
	}
}