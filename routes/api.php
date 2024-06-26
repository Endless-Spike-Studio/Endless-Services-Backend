<?php

use App\EndlessProxy\Controllers\GameApiProxyController;
use App\EndlessProxy\Controllers\GameSfxApiProxyController;
use App\EndlessProxy\Controllers\GameSongApiProxyController;
use App\EndlessProxy\Controllers\NewgroundsAudioProxyController;
use Illuminate\Support\Facades\Route;

Route::group([
	'prefix' => 'EndlessProxy'
], function () {
	Route::group([
		'prefix' => 'GeometryDash'
	], function () {
		Route::post('/getCustomContentURL.php', [GameSfxApiProxyController::class, 'base']);

		Route::group([
			'prefix' => 'CustomContent'
		], function () {
			Route::get('/{path}', [GameSfxApiProxyController::class, 'handle'])->where('path', '.*');
		});

		Route::post('/getGJSongInfo.php', [GameSongApiProxyController::class, 'object']);
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
				Route::get('/', [NewgroundsAudioProxyController::class, 'info']);
				Route::get('/object', [NewgroundsAudioProxyController::class, 'object']);
				Route::get('/download', [NewgroundsAudioProxyController::class, 'download']);
			});
		});
	});
});