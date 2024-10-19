<?php

use App\Base\Controllers\UserController;
use App\EndlessProxy\Controllers\GameAccountDataProxyController as EndlessProxyGameAccountDataProxyController;
use App\EndlessProxy\Controllers\GameApiProxyController as EndlessProxyGameApiProxyController;
use App\EndlessProxy\Controllers\GameCustomContentProxyController as EndlessProxyGameCustomContentProxyController;
use App\EndlessProxy\Controllers\GameSongApiProxyController as EndlessProxyGameSongApiProxyController;
use App\EndlessProxy\Controllers\NewgroundsAudioProxyController as EndlessProxyNewgroundsAudioProxyController;
use App\EndlessServer\Controllers\GameAccountController as EndlessServerGameAccountController;
use App\EndlessServer\Controllers\GameItemController as EndlessServerGameItemController;
use App\EndlessServer\Controllers\GamePlayerDataController as EndlessServerGamePlayerDataController;
use App\EndlessServer\Controllers\GameSongController as EndlessServerGameSongController;
use Illuminate\Support\Facades\Route;

Route::group([
	'prefix' => 'Base'
], function () {
	Route::group([
		'prefix' => 'User'
	], function () {
		Route::post('/register', [UserController::class, 'register']);
		Route::post('/login', [UserController::class, 'login']);
	});
});

Route::group([
	'prefix' => 'EndlessProxy'
], function () {
	Route::group([
		'prefix' => 'GeometryDash'
	], function () {
		Route::get('/network/websocket', [EndlessProxyGameApiProxyController::class, 'getNetworkWebsocketInfo']);
		Route::get('/network/{key}', [EndlessProxyGameApiProxyController::class, 'pullNetwork']);

		Route::post('/getAccountURL.php', [EndlessProxyGameAccountDataProxyController::class, 'base']);

		Route::group([
			'prefix' => 'AccountData'
		], function () {
			Route::post('/{path}', [EndlessProxyGameAccountDataProxyController::class, 'handle'])->where('path', '.*');
		});

		Route::post('/getCustomContentURL.php', [EndlessProxyGameCustomContentProxyController::class, 'base']);

		Route::group([
			'prefix' => 'CustomContent'
		], function () {
			Route::get('/{path}', [EndlessProxyGameCustomContentProxyController::class, 'handle'])->where('path', '.*');
		});

		Route::post('/getGJSongInfo.php', [EndlessProxyGameSongApiProxyController::class, 'object']);

		Route::post('/{path}', [EndlessProxyGameApiProxyController::class, 'handle'])->where('path', '.*');
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
				Route::get('/', [EndlessProxyNewgroundsAudioProxyController::class, 'info']);
				Route::get('/object', [EndlessProxyNewgroundsAudioProxyController::class, 'object']);
				Route::get('/download', [EndlessProxyNewgroundsAudioProxyController::class, 'download']);
			});
		});
	});
});

Route::group([
	'prefix' => 'EndlessServer'
], function () {
	Route::group([
		'prefix' => 'GeometryDash'
	], function () {
		Route::post('/accounts/registerGJAccount.php', [EndlessServerGameAccountController::class, 'register']);
		Route::post('/accounts/loginGJAccount.php', [EndlessServerGameAccountController::class, 'login']);
		Route::post('/updateGJUserScore22.php', [EndlessServerGamePlayerDataController::class, 'update']);
		// Route::post('/getGJUserInfo20.php', []);
		// Route::post('/updateGJAccSettings20.php', []);
		// Route::post('/uploadGJAccComment20.php', []);
		// Route::post('/getGJAccountComments20.php', []);
		// Route::post('/deleteGJAccComment20.php', []);
		// Route::post('/getAccountURL.php', []);
		// Route::post('/database/accounts/backupGJAccountNew.php', []);
		// Route::post('/database/accounts/syncGJAccountNew.php', []);
		// Route::post('/getGJRewards.php', []);
		// Route::post('/requestUserAccess.php', []);
		// Route::post('/getGJChallenges.php', []);
		// Route::post('/getGJUsers20.php', []);
		// Route::post('/getGJScores20.php', []);
		// Route::post('/uploadGJMessage20.php', []);
		// Route::post('/getGJMessages20.php', []);
		// Route::post('/downloadGJMessage20.php', []);
		// Route::post('/deleteGJMessages20.php', []);
		// Route::post('/uploadGJLevel21.php', []);
		// Route::post('/getGJLevels21.php', []);
		// Route::post('/uploadFriendRequest20.php', []);
		// Route::post('/readGJFriendRequest20.php', []);
		// Route::post('/downloadGJLevel22.php', []);
		// Route::post('/reportGJLevel.php', []);
		// Route::post('/deleteGJLevelUser20.php', []);
		// Route::post('/updateGJDesc20.php', []);
		// Route::post('/uploadGJComment21.php', []);
		// Route::post('/getGJComments21.php', []);
		// Route::post('/deleteGJComment20.php', []);
		// Route::post('/deleteGJFriendRequests20.php', []);
		// Route::post('/getGJFriendRequests20.php', []);
		// Route::post('/blockGJUser20.php', []);
		// Route::post('/unblockGJUser20.php', []);
		// Route::post('/getGJUserList20.php', []);
		// Route::post('/removeGJFriend20.php', []);
		// Route::post('/acceptGJFriendRequest20.php', []);
		// Route::post('/getGJCommentHistory.php', []);
		// Route::post('/getGJMapPacks21.php', []);
		// Route::post('/getGJDailyLevel.php', []);
		// Route::post('/getGJGauntlets21.php', []);
		// Route::post('/likeGJItem211.php', []);
		// Route::post('/getGJLevelScores211.php', []);
		// Route::post('/rateGJStars211.php', []);
		// Route::post('/rateGJDemon21.php', []);
		// Route::post('/suggestGJStars20.php', []);
		Route::post('/getGJSongInfo.php', [EndlessServerGameSongController::class, 'getInfo']);
		Route::post('/restoreGJItems.php', [EndlessServerGameItemController::class, 'restore']);
		// Route::post('/getGJTopArtists.php', []);
		// Route::post('/deleteGJLevelList.php', []);
		// Route::post('/getCustomContentURL.php', []);
		// Route::post('/getGJLevelLists.php', []);
		// Route::post('/getGJLevelScoresPlat.php', []);
		// Route::post('/uploadGJLevelList.php', []);
	});
});