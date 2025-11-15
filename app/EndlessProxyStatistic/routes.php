<?php

use App\EndlessProxyStatistic\Controllers\EndlessProxyStatisticController;

Route::group([
	'prefix' => 'EndlessProxy'
], function () {
	Route::group([
		'prefix' => 'Statistic'
	], function () {
		Route::get('/websocket', [EndlessProxyStatisticController::class, 'getWebsocketInfo']);
		Route::get('/initialize', [EndlessProxyStatisticController::class, 'dispatchInitialEvent']);
	});
});