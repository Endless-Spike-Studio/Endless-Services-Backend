<?php

use App\EndlessServer\Controllers\AccountController;
use App\EndlessServer\Controllers\GameAccountBlocklistController;
use App\EndlessServer\Controllers\GameAccountCommentController;
use App\EndlessServer\Controllers\GameAccountController;
use App\EndlessServer\Controllers\GameAccountDataController;
use App\EndlessServer\Controllers\GameAccountFriendController;
use App\EndlessServer\Controllers\GameAccountFriendRequestController;
use App\EndlessServer\Controllers\GameAccountSettingController;
use App\EndlessServer\Controllers\GameCustomContentController;
use App\EndlessServer\Controllers\GameLeaderboardController;
use App\EndlessServer\Controllers\GameLevelCommentController;
use App\EndlessServer\Controllers\GameLevelController;
use App\EndlessServer\Controllers\GameLevelGauntletController;
use App\EndlessServer\Controllers\GameLevelListController;
use App\EndlessServer\Controllers\GameLevelRatingSuggestController;
use App\EndlessServer\Controllers\GameLevelScoreController;
use App\EndlessServer\Controllers\GameLikeController;
use App\EndlessServer\Controllers\GameMapPackController;
use App\EndlessServer\Controllers\GameMessageController;
use App\EndlessServer\Controllers\GamePlayerController;
use App\EndlessServer\Controllers\GamePlayerDataController;
use App\EndlessServer\Controllers\GameQuestController;
use App\EndlessServer\Controllers\GameRewardController;
use App\EndlessServer\Controllers\GameSecretRewardController;
use App\EndlessServer\Controllers\GameSongController;

Route::group([
	'prefix' => 'EndlessServer'
], function () {
	Route::group([
		'prefix' => 'Account'
	], function () {
		Route::post('/verify', [AccountController::class, 'verify']);
	});

	Route::group([
		'prefix' => 'GeometryDash'
	], function () {
		Route::group([
			'prefix' => 'accounts'
		], function () {
			Route::post('/registerGJAccount.php', [GameAccountController::class, 'register']);
			Route::post('/loginGJAccount.php', [GameAccountController::class, 'login']);
		});

		Route::post('/updateGJUserScore22.php', [GamePlayerDataController::class, 'update']);
		Route::post('/getGJUserInfo20.php', [GamePlayerController::class, 'info']);

		Route::post('/updateGJAccSettings20.php', [GameAccountSettingController::class, 'update']);

		Route::post('/uploadGJAccComment20.php', [GameAccountCommentController::class, 'upload']);
		Route::post('/getGJAccountComments20.php', [GameAccountCommentController::class, 'list']);
		Route::post('/deleteGJAccComment20.php', [GameAccountCommentController::class, 'delete']);

		Route::post('/getAccountURL.php', [GameAccountDataController::class, 'baseUrl']);

		Route::group([
			'prefix' => 'database'
		], function () {
			Route::group([
				'prefix' => 'accounts'
			], function () {
				Route::post('/backupGJAccountNew.php', [GameAccountDataController::class, 'save']);
				Route::post('/syncGJAccountNew.php', [GameAccountDataController::class, 'load']);
			});
		});

		Route::post('/getGJRewards.php', [GameRewardController::class, 'get']);

		Route::post('/requestUserAccess.php', [GameAccountController::class, 'requestAccess']);

		Route::post('/getGJChallenges.php', [GameQuestController::class, 'get']);

		Route::post('/getGJUsers20.php', [GamePlayerController::class, 'search']);

		Route::post('/getGJScores20.php', [GameLeaderboardController::class, 'list']);

		Route::post('/getGJUserList20.php', [GamePlayerController::class, 'list']);

		Route::post('/uploadGJMessage20.php', [GameMessageController::class, 'send']);
		Route::post('/getGJMessages20.php', [GameMessageController::class, 'list']);
		Route::post('/downloadGJMessage20.php', [GameMessageController::class, 'download']);
		Route::post('/deleteGJMessages20.php', [GameMessageController::class, 'delete']);

		Route::post('/uploadFriendRequest20.php', [GameAccountFriendRequestController::class, 'send']);
		Route::post('/readGJFriendRequest20.php', [GameAccountFriendRequestController::class, 'read']);
		Route::post('/deleteGJFriendRequests20.php', [GameAccountFriendRequestController::class, 'delete']);
		Route::post('/getGJFriendRequests20.php', [GameAccountFriendRequestController::class, 'list']);
		Route::post('/acceptGJFriendRequest20.php', [GameAccountFriendRequestController::class, 'accept']);

		Route::post('/removeGJFriend20.php', [GameAccountFriendController::class, 'delete']);

		Route::post('/blockGJUser20.php', [GameAccountBlocklistController::class, 'add']);
		Route::post('/unblockGJUser20.php', [GameAccountBlocklistController::class, 'delete']);

		Route::post('/uploadGJLevel21.php', [GameLevelController::class, 'upload']);
		Route::post('/getGJLevels21.php', [GameLevelController::class, 'search']);
		Route::post('/downloadGJLevel22.php', [GameLevelController::class, 'download']);
		Route::post('/reportGJLevel.php', [GameLevelController::class, 'report']);
		Route::post('/deleteGJLevelUser20.php', [GameLevelController::class, 'delete']);
		Route::post('/updateGJDesc20.php', [GameLevelController::class, 'updateDescription']);

		Route::post('/uploadGJComment21.php', [GameLevelCommentController::class, 'upload']);
		Route::post('/getGJComments21.php', [GameLevelCommentController::class, 'list']);
		Route::post('/deleteGJComment20.php', [GameLevelCommentController::class, 'delete']);

		Route::post('/getGJCommentHistory.php', [GameLevelCommentController::class, 'history']);

		Route::post('/getGJMapPacks21.php', [GameMapPackController::class, 'list']);

		Route::post('/getGJDailyLevel.php', [GameLevelController::class, 'getSpecial']);

		Route::post('/getGJGauntlets21.php', [GameLevelGauntletController::class, 'list']);

		Route::post('/likeGJItem211.php', [GameLikeController::class, 'forItem']);

		Route::post('/getGJLevelScores211.php', [GameLevelScoreController::class, 'loadNormal']);
		Route::post('/getGJLevelScoresPlat.php', [GameLevelScoreController::class, 'loadPlatformer']);

		Route::post('/rateGJStars211.php', [GameLevelRatingSuggestController::class, 'voteStars']);
		Route::post('/rateGJDemon21.php', [GameLevelRatingSuggestController::class, 'voteDemon']);
		Route::post('/suggestGJStars20.php', [GameLevelRatingSuggestController::class, 'suggestStars']);

		Route::post('/getGJSongInfo.php', [GameSongController::class, 'getInfo']);
		Route::post('/getGJTopArtists.php', [GameSongController::class, 'getTopArtists']);

		Route::post('/getGJLevelLists.php', [GameLevelListController::class, 'list']);
		Route::post('/uploadGJLevelList.php', [GameLevelListController::class, 'upload']);
		Route::post('/deleteGJLevelList.php', [GameLevelListController::class, 'delete']);

		Route::post('/getGJSecretReward.php', [GameSecretRewardController::class, 'get']);

		Route::post('/getCustomContentURL.php', [GameCustomContentController::class, 'getURL']);

		Route::group([
			'prefix' => 'CustomContent'
		], function () {
			Route::post('/{path}', [GameCustomContentController::class, 'handle'])->where('path', '.*');
		});
	});
});