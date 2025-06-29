<?php

use App\EndlessBase\Controllers\UserController;
use App\EndlessBase\Controllers\WebsocketController;

Route::group([
	'prefix' => 'EndlessBase'
], function () {
	Route::get('/websocket', [WebsocketController::class, 'getInfo']);

	Route::group([
		'prefix' => 'User'
	], function () {
		Route::post('/register', [UserController::class, 'register']);
		Route::post('/login', [UserController::class, 'login']);
	});
});