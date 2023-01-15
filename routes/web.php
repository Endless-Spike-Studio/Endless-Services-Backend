<?php

use App\Http\Controllers\GDCS\Web\AccountController;
use App\Http\Controllers\GDCS\Web\AccountLinkToolController;
use App\Http\Controllers\GDCS\Web\AuthController;
use App\Http\Controllers\GDCS\Web\ContestController;
use App\Http\Controllers\GDCS\Web\CustomSongToolController;
use App\Http\Controllers\GDCS\Web\LevelController;
use App\Http\Controllers\GDCS\Web\LevelTempUploadAccessToolController;
use App\Http\Controllers\GDCS\Web\LevelTransferToolController;
use App\Http\Presenters\GDCS\AccountLinkToolPresenter;
use App\Http\Presenters\GDCS\AccountPresenter;
use App\Http\Presenters\GDCS\CustomSongToolPresenter;
use App\Http\Presenters\GDCS\DashboardPresenter;
use App\Http\Presenters\GDCS\HomePresenter as GDCS_HomePresenter;
use App\Http\Presenters\GDCS\LevelTempUploadAccessToolPresenter;
use App\Http\Presenters\GDCS\LevelTransferToolPresenter;
use App\Http\Presenters\NGProxy\HomePresenter as NGProxy_HomePresenter;
use Illuminate\Support\Facades\Route;

Route::group([
    'domain' => 'gf.geometrydashchinese.com',
    'as' => 'gdcs.'
], static function () {
    Route::get('/', [GDCS_HomePresenter::class, 'render'])->name('home');

    Route::group([
        'prefix' => 'auth',
        'as' => 'auth.'
    ], static function () {
        Route::group([
            'middleware' => 'guest:gdcs'
        ], static function () {
            Route::inertia('/register', 'GDCS/Auth/Register')->name('register');
            Route::post('/register', [AuthController::class, 'register'])->name('register.api');
            Route::inertia('/login', 'GDCS/Auth/Login')->name('login');
            Route::post('/login', [AuthController::class, 'login'])->name('login.api');
        });

        Route::group([
            'middleware' => 'auth:gdcs'
        ], static function () {
            Route::get('/verify/{_}', [AuthController::class, 'verify'])
                ->middleware(['signed'])
                ->name('verify');

            Route::post('/logout', [AuthController::class, 'logout'])->name('logout.api');
        });
    });

    Route::group([
        'middleware' => 'auth:gdcs'
    ], static function () {
        Route::group([
            'prefix' => 'dashboard',
            'as' => 'dashboard.'
        ], static function () {
            Route::get('/', [DashboardPresenter::class, 'renderHome'])->name('home');

            Route::group([
                'prefix' => 'account',
                'as' => 'account.'
            ], static function () {
                Route::post('/resendVerificationEmail', [AccountController::class, 'resendVerificationEmail'])->name('resendVerificationEmail.api');
                Route::get('/{account}', [AccountPresenter::class, 'renderInfo'])->name('info');
                Route::post('/{account}/edit', [AccountController::class, 'edit'])->name('edit.api');
                Route::post('/{account}/changePassword', [AccountController::class, 'changePassword'])->name('changePassword.api');
            });

            Route::group([
                'prefix' => 'level',
                'as' => 'level.'
            ], static function () {
                Route::get('/{level}', [DashboardPresenter::class, 'renderLevelInfo'])->name('info');
                Route::post('/{level}/edit', [LevelController::class, 'edit'])->name('edit.api');
                Route::delete('/{level}', [LevelController::class, 'delete'])->name('delete.api');
            });

            Route::group([
                'prefix' => 'contest',
                'as' => 'contest.'
            ], static function () {
                Route::get('/{contest}', [DashboardPresenter::class, 'renderContestInfo'])->name('info');
                Route::post('/{contest}/submit', [ContestController::class, 'submit'])->name('submit.api');
            });
        });

        Route::group([
            'prefix' => 'tools',
            'as' => 'tools.'
        ], static function () {
            Route::inertia('/', 'GDCS/Tools/Home')->name('home');

            Route::group([
                'prefix' => 'account',
                'as' => 'account.'
            ], static function () {
                Route::group([
                    'prefix' => 'link',
                    'as' => 'link.'
                ], static function () {
                    Route::get('/', [AccountLinkToolPresenter::class, 'renderIndex'])->name('index');
                    Route::inertia('/create', 'GDCS/Tools/Account/Link/Create')->name('create');
                    Route::post('/create', [AccountLinkToolController::class, 'create'])->name('create.api');
                    Route::delete('/{link}', [AccountLinkToolController::class, 'delete'])->name('delete.api');
                });
            });

            Route::group([
                'prefix' => 'level',
                'as' => 'level.'
            ], static function () {
                Route::group([
                    'prefix' => 'transfer',
                    'as' => 'transfer.'
                ], static function () {
                    Route::get('/', [LevelTransferToolPresenter::class, 'renderHome'])->name('home');
                    Route::get('/in/{link}', [LevelTransferToolPresenter::class, 'renderIn'])->name('in');
                    Route::post('/in/{link}', [LevelTransferToolController::class, 'in'])->name('in.api');
                    Route::get('/out/{level}', [LevelTransferToolPresenter::class, 'renderOut'])->name('out');
                    Route::post('/out/{level}', [LevelTransferToolController::class, 'out'])->name('out.api');
                });

                Route::group([
                    'prefix' => 'tempUploadAccess',
                    'as' => 'temp_upload_access.'
                ], static function () {
                    Route::get('/', [LevelTempUploadAccessToolPresenter::class, 'renderIndex'])->name('index');
                    Route::post('/create', [LevelTempUploadAccessToolController::class, 'create'])->name('create.api');
                    Route::delete('/{access}', [LevelTempUploadAccessToolController::class, 'delete'])->name('delete.api');
                });
            });

            Route::group([
                'prefix' => 'song',
                'as' => 'song.'
            ], static function () {
                Route::group([
                    'prefix' => 'custom',
                    'as' => 'custom.'
                ], static function () {
                    Route::get('/', [CustomSongToolPresenter::class, 'renderIndex'])->name('index');
                    Route::get('/uploaded', [CustomSongToolPresenter::class, 'renderUploaded'])->name('uploaded');
                    Route::inertia('/create', 'GDCS/Tools/Song/Custom/Create')->name('create');
                    Route::delete('/{song}', [CustomSongToolController::class, 'delete'])->name('delete.api');
                    Route::post('/create/link', [CustomSongToolController::class, 'createUsingLink'])->name('create.link.api');
                    Route::post('/create/file', [CustomSongToolController::class, 'createUsingFile'])->name('create.file.api');
                    Route::post('/create/netease', [CustomSongToolController::class, 'createUsingNetease'])->name('create.netease.api');
                });
            });
        });
    });
});

Route::group([
    'domain' => 'dl.geometrydashchinese.com',
    'as' => 'gdproxy.'
], static function () {
    Route::inertia('/', 'GDProxy/Home')->name('home');
});

Route::group([
    'domain' => 'ng.geometrydashchinese.com',
    'as' => 'ngproxy.'
], static function () {
    Route::inertia('/', 'NGProxy/Home')->name('home');
    Route::get('/{id}', [NGProxy_HomePresenter::class, 'renderInfo'])
        ->where('song', '\d+')
        ->name('info');
});
