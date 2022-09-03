<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\NGProxy\SongController;
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
    Route::get('/_internal/bdb58739787c90e980458df6d36e6928', [ApiController::class, 'removeOutdatedManifestCache'])->name('manifest_cache.remove');

    Route::group([
        'domain' => 'ng.geometrydashchinese.com',
        'as' => 'ngproxy.',
    ], static function () {
        Route::get('/{id}/info', [SongController::class, 'info'])
            ->where('id', '\d+')
            ->name('info');

        Route::get('/{id}/object', [SongController::class, 'object'])
            ->where('id', '\d+')
            ->name('object');

        Route::get('/{id}/download', [SongController::class, 'download'])
            ->where('id', '\d+')
            ->name('download');
    });
});
