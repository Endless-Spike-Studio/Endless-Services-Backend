<?php

namespace App\EndlessBase\Controllers;

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
			'forceTLS' => config('services.websocket.force_tls'),
			'enabledTransports' => config('services.websocket.enabled_transports'),
			'authEndpoint' => URL::action([BroadcastController::class, 'authenticate'])
		]);
	}
}