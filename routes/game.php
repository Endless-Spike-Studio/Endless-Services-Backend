<?php

use App\Http\Controllers\GDCS\Game\AccountBlockController;
use App\Http\Controllers\GDCS\Game\AccountCommentController;
use App\Http\Controllers\GDCS\Game\AccountCommentHistoryController;
use App\Http\Controllers\GDCS\Game\AccountController;
use App\Http\Controllers\GDCS\Game\AccountDataController;
use App\Http\Controllers\GDCS\Game\AccountFriendController;
use App\Http\Controllers\GDCS\Game\AccountFriendRequestController;
use App\Http\Controllers\GDCS\Game\AccountMessageController;
use App\Http\Controllers\GDCS\Game\AccountSettingController;
use App\Http\Controllers\GDCS\Game\ChallengeController;
use App\Http\Controllers\GDCS\Game\RewardController;
use App\Http\Controllers\GDCS\Game\SongController;
use App\Http\Controllers\GDCS\Game\UserController;
use App\Http\Controllers\GDCS\Game\UserScoreController;
use App\Http\Controllers\GDCS\ItemController;
use App\Http\Controllers\GDCS\LeaderboardController;
use App\Http\Controllers\GDCS\LevelCommentController;
use App\Http\Controllers\GDCS\LevelController;
use App\Http\Controllers\GDCS\LevelGauntletController;
use App\Http\Controllers\GDCS\LevelLeaderboardController;
use App\Http\Controllers\GDCS\LevelPackController;
use App\Http\Controllers\GDCS\LevelRatingController;
use App\Http\Controllers\GDProxyController;
use App\Http\Controllers\NGProxy\SongController as NGProxySongController;
use Illuminate\Support\Facades\Route;

Route::group([
    'as' => 'game.',
], static function () {
    Route::group([
        'domain' => 'gf.geometrydashchinese.com',
        'as' => 'gdcs.',
    ], static function () {
        Route::post('/accounts/registerGJAccount.php', [AccountController::class, 'register'])->name('account.register');
        Route::post('/accounts/loginGJAccount.php', [AccountController::class, 'login'])->name('account.login');
        Route::post('/updateGJUserScore22.php', [UserScoreController::class, 'update'])->name('user.score.update');
        Route::post('/getGJUserInfo20.php', [AccountController::class, 'fetchInfo'])->name('user.info');
        Route::post('/updateGJAccSettings20.php', [AccountSettingController::class, 'update'])->name('account.setting.update');
        Route::post('/uploadGJAccComment20.php', [AccountCommentController::class, 'create'])->name('account.comment.upload');
        Route::post('/getGJAccountComments20.php', [AccountCommentController::class, 'index'])->name('account.comment.get');
        Route::post('/deleteGJAccComment20.php', [AccountCommentController::class, 'delete'])->name('account.comment.delete');
        Route::post('/getAccountURL.php', [AccountDataController::class, 'getDataServerAddress'])->name('account.url.get');
        Route::post('/database/accounts/backupGJAccountNew.php', [AccountDataController::class, 'save'])->name('account.data.backup');
        Route::post('/database/accounts/syncGJAccountNew.php', [AccountDataController::class, 'load'])->name('account.data.sync');
        Route::post('/getGJRewards.php', [RewardController::class, 'fetch'])->name('reward.get');
        Route::post('/requestUserAccess.php', [AccountController::class, 'requestModAccess'])->name('user.access.request');
        Route::post('/getGJChallenges.php', [ChallengeController::class, 'fetch'])->name('challenge.get');
        Route::post('/getGJUsers20.php', [UserController::class, 'search'])->name('user.get');
        Route::post('/getGJScores20.php', [LeaderboardController::class, 'fetchAll'])->name('score.get');
        Route::post('/uploadGJMessage20.php', [AccountMessageController::class, 'send'])->name('message.upload');
        Route::post('/getGJMessages20.php', [AccountMessageController::class, 'fetchAll'])->name('message.get');
        Route::post('/downloadGJMessage20.php', [AccountMessageController::class, 'fetch'])->name('message.download');
        Route::post('/deleteGJMessages20.php', [AccountMessageController::class, 'delete'])->name('message.delete');
        Route::post('/uploadGJLevel21.php', [LevelController::class, 'upload'])->name('level.upload');
        Route::post('/getGJLevels21.php', [LevelController::class, 'search'])->name('level.get');
        Route::post('/uploadFriendRequest20.php', [AccountFriendRequestController::class, 'send'])->name('friend.request.upload');
        Route::post('/downloadGJLevel22.php', [LevelController::class, 'download'])->name('level.download');
        Route::post('/deleteGJLevelUser20.php', [LevelController::class, 'delete'])->name('level.delete');
        Route::post('/updateGJDesc20.php', [LevelController::class, 'updateDesc'])->name('level.desc.update');
        Route::post('/uploadGJComment21.php', [LevelCommentController::class, 'create'])->name('level.comment.upload');
        Route::post('/getGJComments21.php', [LevelCommentController::class, 'fetchAll'])->name('level.comment.get');
        Route::post('/deleteGJComment20.php', [LevelCommentController::class, 'delete'])->name('level.comment.delete');
        Route::post('/deleteGJFriendRequests20.php', [AccountFriendRequestController::class, 'delete'])->name('friend.request.delete');
        Route::post('/getGJFriendRequests20.php', [AccountFriendRequestController::class, 'index'])->name('friend.request.get');
        Route::post('/blockGJUser20.php', [AccountBlockController::class, 'block'])->name('account.block');
        Route::post('/unblockGJUser20.php', [AccountBlockController::class, 'unblock'])->name('account.unblock');
        Route::post('/getGJUserList20.php', [UserController::class, 'index'])->name('user.list.get');
        Route::post('/removeGJFriend20.php', [AccountFriendController::class, 'remove'])->name('friend.remove');
        Route::post('/acceptGJFriendRequest20.php', [AccountFriendRequestController::class, 'accept'])->name('friend.request.accept');
        Route::post('/getGJCommentHistory.php', [AccountCommentHistoryController::class, 'index'])->name('account.comment.history.get');
        Route::post('/getGJMapPacks21.php', [LevelPackController::class, 'fetchAll'])->name('map.pack.get');
        Route::post('/getGJDailyLevel.php', [LevelController::class, 'fetchDailyOrWeekly'])->name('daily.level.get');
        Route::post('/getGJGauntlets21.php', [LevelGauntletController::class, 'fetchAll'])->name('gauntlet.get');
        Route::post('/likeGJItem211.php', [ItemController::class, 'like'])->name('like');
        Route::post('/getGJLevelScores211.php', [LevelLeaderboardController::class, 'fetchAll'])->name('level.score.get');
        Route::post('/rateGJStars211.php', [LevelRatingController::class, 'rateStars'])->name('level.rate.stars');
        Route::post('/rateGJDemon21.php', [LevelRatingController::class, 'rateDemon'])->name('level.rate.demon');
        Route::post('/suggestGJStars20.php', [LevelRatingController::class, 'suggestStars'])->name('level.suggest.stars');
        Route::post('/getGJSongInfo.php', [SongController::class, 'fetch'])->name('song.info.get');
        Route::post('/restoreGJItems.php', [ItemController::class, 'restore'])->name('item.restore');
        Route::post('/getGJTopArtists.php', [SongController::class, 'fetchTopArtists'])->name('song.top.artists.get');
    });

    Route::group([
        'domain' => 'dl.geometrydashchinese.com',
        'as' => 'gdproxy.',
    ], static function () {
        Route::post('/{path}', [GDProxyController::class, 'process'])
            ->where('path', '.*')
            ->name('proxy');
    });

    Route::group([
        'domain' => 'ng.geometrydashchinese.com',
        'as' => 'ngproxy.',
    ], static function () {
        Route::post('/getGJSongInfo.php', [NGProxySongController::class, 'objectForGD'])->name('object');
    });
});
