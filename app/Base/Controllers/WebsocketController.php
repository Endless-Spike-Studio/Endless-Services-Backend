<?php

namespace App\Base\Controllers;

use App\Api\Responses\SuccessResponse;
use Illuminate\Broadcasting\BroadcastController;
use Illuminate\Support\Facades\URL;

readonly class WebsocketController
{
	public function getInfo(): SuccessResponse
	{
		return new SuccessResponse([
			'broadcaster' => 'reverb',
			'key' => config('broadcasting.connections.reverb.key'),
			'wsHost' => config('services.websocket.host'),
			'wsPath' => config('services.websocket.path'),
			'wsPort' => config('services.websocket.ws_port'),
			'wssPort' => config('services.websocket.wss_port'),
			'forceTLS' => false,
			'enabledTransports' => ['ws', 'wss'],
			'authEndpoint' => URL::action([BroadcastController::class, 'authenticate'])
		]);
	}
}