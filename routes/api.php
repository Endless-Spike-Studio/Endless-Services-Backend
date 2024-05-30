<?php

use App\GeometryDashProxy\Controllers\GameApiController;
use App\NewgroundsProxy\Controllers\SongApiController;

Route::group([
	'prefix' => 'EndlessProxy'
], function () {
	Route::post('/getGJSongInfo.php', [GameApiController::class, 'getSong']);
	Route::post('/{path}', [GameApiController::class, 'proxy'])->where('path', '\.php$');

	Route::group([
		'prefix' => 'songs'
	], function () {
		Route::get('/{id}', [SongApiController::class, 'info']);
		Route::get('/{id}/object', [SongApiController::class, 'object']);
		Route::get('/{id}/download', [SongApiController::class, 'download']);
	});
});