<?php

use App\Http\Controllers\GDCS\CustomSongApiController as GDCS_CustomSongController;
use App\Http\Controllers\GDProxy\CustomSongController as GDProxy_CustomSongController;
use App\Http\Controllers\NGProxy\SongController as NGProxySongController;
use Illuminate\Support\Facades\Route;
use Tightenco\Ziggy\Ziggy;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'as' => 'api.',
], static function () {
    Route::get('/routes', [Ziggy::class, 'toArray'])->name('ziggy.routes');

    Route::group([
        'domain' => 'gf.geometrydashchinese.com',
        'as' => 'gdcs.',
    ], static function () {
        Route::get('/customSong/{id}/download', [GDCS_CustomSongController::class, 'download'])
            ->where('id', '\d+')
            ->name('customSong.download');
    });

    Route::group([
        'domain' => 'dl.geometrydashchinese.com',
        'as' => 'gdproxy.',
    ], static function () {
        Route::get('/customSong/{id}/download', [GDProxy_CustomSongController::class, 'download'])
            ->where('id', '\d+')
            ->name('customSong.download');
    });

    Route::group([
        'domain' => 'ng.geometrydashchinese.com',
        'as' => 'ngproxy.',
    ], static function () {
        Route::get('/{id}/info', [NGProxySongController::class, 'info'])
            ->where('id', '\d+')
            ->name('info');

        Route::get('/{id}/object', [NGProxySongController::class, 'object'])
            ->where('id', '\d+')
            ->name('object');

        Route::get('/{id}/download', [NGProxySongController::class, 'download'])
            ->where('id', '\d+')
            ->name('download');
    });
});
