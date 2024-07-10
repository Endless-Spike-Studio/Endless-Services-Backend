<?php

use App\Base\Controllers\UserController;
use App\EndlessProxy\Controllers\GameAccountDataProxyController;
use App\EndlessProxy\Controllers\GameApiProxyController;
use App\EndlessProxy\Controllers\GameCustomContentProxyController;
use App\EndlessProxy\Controllers\GameSongApiProxyController;
use App\EndlessProxy\Controllers\NewgroundsAudioProxyController;
use Illuminate\Support\Facades\Route;

Route::group([
	'prefix' => 'Base'
], function () {
	Route::group([
		'prefix' => 'User'
	], function () {
		Route::post('/register', [UserController::class, 'register']);
	});
});

Route::group([
	'prefix' => 'EndlessProxy'
], function () {
	Route::group([
		'prefix' => 'GeometryDash'
	], function () {
		Route::post('/getAccountURL.php', [GameAccountDataProxyController::class, 'base']);

		Route::group([
			'prefix' => 'AccountData'
		], function () {
			Route::post('/{path}', [GameAccountDataProxyController::class, 'handle'])->where('path', '.*');
		});

		Route::post('/getCustomContentURL.php', [GameCustomContentProxyController::class, 'base']);

		Route::group([
			'prefix' => 'CustomContent'
		], function () {
			Route::get('/{path}', [GameCustomContentProxyController::class, 'handle'])->where('path', '.*');
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