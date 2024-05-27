<?php

use App\GeometryDashProxy\Controllers\GameApiController;

Route::group([
	'prefix' => 'GeometryDashProxy'
], function () {
	Route::post('/{path}', [GameApiController::class, 'proxy'])->where('path', '.*');
});