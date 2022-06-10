<?php

use App\Http\Controllers\GDCS\AccountController;
use App\Http\Controllers\GDCS\AccountLinkController;
use App\Http\Controllers\GDCS\CustomSongController;
use App\Http\Controllers\GDCS\LevelTempUploadAccessController;
use App\Http\Controllers\GDCS\LevelTransferController;
use App\Http\Controllers\GDCS\Web\AccountFailedLogController;
use App\Http\Controllers\UserController;
use App\Http\Presenters\GDCN\Dashboard\UserPresenter;
use App\Http\Presenters\GDCS\Dashboard\AccountPresenter;
use App\Http\Presenters\GDCS\Dashboard\InformationPresenter;
use App\Http\Presenters\GDCS\DashboardPresenter;
use App\Http\Presenters\GDCS\Tools\AccountLinkPresenter;
use App\Http\Presenters\GDCS\Tools\CustomSongPresenter;
use App\Http\Presenters\GDCS\Tools\LevelTempUploadAccessPresenter;
use App\Http\Presenters\GDCS\Tools\LevelTransferPresenter;
use App\Http\Presenters\NGProxyPresenter;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group([
    'domain' => 'fw.geometrydashchinese.com'
], static function () {
    Route::inertia('/', 'GDCN/Home')->name('home');

    Route::group([
        'middleware' => 'guest'
    ], static function () {
        Route::inertia('/register', 'GDCN/Auth/Register')->name('register');
        Route::post('/register', [UserController::class, 'register'])->name('register.api');

        Route::inertia('/login', 'GDCN/Auth/Login')->name('login');
        Route::post('/login', [UserController::class, 'login'])->name('login.api');
    });

    Route::group([
        'middleware' => 'auth'
    ], static function () {
        Route::get('/verify/{_}', [UserController::class, 'verify'])
            ->middleware('signed')
            ->name('verification.verify');

        Route::group([
            'prefix' => 'user',
            'as' => 'user.'
        ], static function () {
            Route::get('/profile', [UserPresenter::class, 'renderProfile'])->name('profile');
            Route::post('/resend:email_verification', [UserController::class, 'resendEmailVerification'])->name('resendEmailVerification.api');
            Route::get('/setting', [UserPresenter::class, 'renderSetting'])->name('setting');
            Route::patch('/setting', [UserController::class, 'updateSetting'])->name('setting.update.api');
            Route::get('/logout', [UserController::class, 'logout'])->name('logout.api');
        });

        Route::group([
            'prefix' => 'dashboard',
            'as' => 'dashboard.'
        ], static function () {
            Route::inertia('/', 'GDCN/Dashboard/Home')->name('home');
        });

        Route::group([
            'prefix' => 'tools',
            'as' => 'tools.'
        ], static function () {
            Route::inertia('/', 'GDCN/Tools/Home')->name('home');
        });
    });
});

Route::group([
    'domain' => 'gf.geometrydashchinese.com',
    'as' => 'gdcs.'
], static function () {
    Route::inertia('/', 'GDCS/Home')->name('home');

    Route::group([
        'middleware' => 'guest:gdcs'
    ], static function () {
        Route::inertia('/register', 'GDCS/Auth/Register')->name('register');
        Route::post('/register', [AccountController::class, 'apiRegister'])->name('register.api');

        Route::inertia('/login', 'GDCS/Auth/Login')->name('login');
        Route::post('/login', [AccountController::class, 'apiLogin'])->name('login.api');
    });

    Route::group([
        'middleware' => 'auth:gdcs'
    ], static function () {
        Route::group([
            'prefix' => 'account',
            'as' => 'account.'
        ], static function () {
            Route::get('/verify', [AccountController::class, 'verify'])
                ->middleware('signed')
                ->name('verify');

            Route::get('/profile', [AccountPresenter::class, 'renderProfile'])->name('profile');
            Route::post('/resend:email_verification', [AccountController::class, 'resendEmailVerification'])->name('resendEmailVerification.api');
            Route::get('/setting', [AccountPresenter::class, 'renderSetting'])->name('setting');
            Route::patch('/setting', [AccountController::class, 'updateSetting'])->name('setting.update.api');
            Route::get('/failed-log', [AccountPresenter::class, 'renderFailedLogs'])->name('failed-log');
            Route::get('/logout', [AccountController::class, 'logout'])->name('logout.api');
            Route::delete('/failed-logs', [AccountFailedLogController::class, 'clear'])->name('failed-log.clear.api');
        });

        Route::group([
            'prefix' => 'dashboard',
            'as' => 'dashboard.'
        ], static function () {
            Route::get('/', [DashboardPresenter::class, 'renderHome'])->name('home');

            Route::get('/account/{id}', [InformationPresenter::class, 'renderAccount'])
                ->where('id', '\d+')
                ->name('account.info');

            Route::get('/level/{id}', [InformationPresenter::class, 'renderLevel'])
                ->where('id', '\d+')
                ->name('level.info');

            Route::get('/user/{id}', [InformationPresenter::class, 'renderUser'])
                ->where('id', '\d+')
                ->name('user.info');
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
                    Route::get('/list', [AccountLinkPresenter::class, 'list'])->name('list');
                    Route::inertia('/create', 'GDCS/Tools/Account/Link/Create')->name('create');

                    Route::post('/create', [AccountLinkController::class, 'create'])->name('create.api');
                    Route::delete('/{id}', [AccountLinkController::class, 'delete'])
                        ->where('id', '\d+')
                        ->name('delete.api');
                });
            });

            Route::group([
                'prefix' => 'level',
                'as' => 'level.'
            ], static function () {
                Route::group([
                    'prefix' => 'tempUploadAccess',
                    'as' => 'temp_upload_access.'
                ], static function () {
                    Route::get('/list', [LevelTempUploadAccessPresenter::class, 'list'])->name('list');
                    Route::get('/create', [LevelTempUploadAccessController::class, 'create'])->name('create.api');

                    Route::delete('/{id}', [LevelTempUploadAccessController::class, 'delete'])
                        ->where('id', '\d+')
                        ->name('delete.api');
                });

                Route::group([
                    'prefix' => 'transfer',
                    'as' => 'transfer.'
                ], static function () {
                    Route::inertia('/', 'GDCS/Tools/Level/Transfer/Home')->name('home');

                    Route::get('/in', [LevelTransferPresenter::class, 'in'])->name('in');
                    Route::post('/in', [LevelTransferController::class, 'transferIn'])->name('in.api');

                    Route::get('/out', [LevelTransferPresenter::class, 'out'])->name('out');
                    Route::post('/out', [LevelTransferController::class, 'transferOut'])->name('out.api');
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
                    Route::get('/list', [CustomSongPresenter::class, 'list'])->name('list');

                    Route::inertia('/create:link', 'GDCS/Tools/Song/Custom/Create/Link')->name('create.link');
                    Route::post('/create:link', [CustomSongController::class, 'createLink'])->name('create.link.api');

                    Route::inertia('/create:netease', 'GDCS/Tools/Song/Custom/Create/Netease')->name('create.netease');
                    Route::post('/create:netease', [CustomSongController::class, 'createNetease'])->name('create.netease.api');

                    Route::delete('/{id}', [CustomSongController::class, 'delete'])
                        ->where('id', '\d+')
                        ->name('delete.api');
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

    Route::get('/{id}', [NGProxyPresenter::class, 'renderHome'])
        ->where('id', '\d+')
        ->name('info');
});
