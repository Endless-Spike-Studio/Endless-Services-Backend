<?php

use App\EndlessProxy\Controllers\GameApiProxyController;
use App\EndlessProxy\Controllers\NewgroundsProxyController;
use Illuminate\Support\Facades\Route;

Route::group([
	'prefix' => 'EndlessProxy'
], function () {
	Route::group([
		'prefix' => 'GeometryDash'
	], function () {
		Route::post('/getGJSongInfo.php', [GameApiProxyController::class, 'getSong']);
		Route::post('/{path}', [GameApiProxyController::class, 'handle'])->where('path', '.*');
	});

	Route::group([
		'prefix' => 'Newgrounds'
	], function () {
		Route::group([
			'prefix' => 'Audios'
		], function () {
			Route::group([
				'prefix' => '{id}'
			], function () {
				Route::get('/', [NewgroundsProxyController::class, 'info']);
				Route::get('/object', [NewgroundsProxyController::class, 'object']);
				Route::get('/download', [NewgroundsProxyController::class, 'download']);
			});
		});
	});
});