<?php

use App\Http\Controllers\GDCS\AccountApiController;
use App\Http\Controllers\GDCS\AccountFailedLogApiController;
use App\Http\Controllers\GDCS\AccountLinkApiController;
use App\Http\Controllers\GDCS\CustomSongApiController;
use App\Http\Controllers\GDCS\LevelApiController;
use App\Http\Controllers\GDCS\LevelTempUploadAccessApiController;
use App\Http\Controllers\GDCS\LevelTransferApiController;
use App\Http\Presenters\GDCS\Admin\AbilityPresenter;
use App\Http\Presenters\GDCS\Admin\LevelPresenter;
use App\Http\Presenters\GDCS\Admin\RolePresenter;
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
    'domain' => 'geometrydashchinese.com',
], static function () {
    Route::inertia('/', 'Home')->name('home');
});

Route::group([
    'domain' => 'gf.geometrydashchinese.com',
    'as' => 'gdcs.',
], static function () {
    Route::inertia('/', 'GDCS/Home')->name('home');

    Route::group([
        'middleware' => 'guest:gdcs',
    ], static function () {
        Route::inertia('/register', 'GDCS/Auth/Register')->name('register');
        Route::post('/register', [AccountApiController::class, 'register'])->name('register.api');

        Route::inertia('/login', 'GDCS/Auth/Login')->name('login');
        Route::post('/login', [AccountApiController::class, 'login'])->name('login.api');
    });

    Route::group([
        'middleware' => 'auth:gdcs',
    ], static function () {
        Route::group([
            'as' => 'admin.',
            'prefix' => 'admin',
        ], static function () {
            Route::group([
                'as' => 'account.',
                'prefix' => 'account',
            ], static function () {
                Route::group([
                    'middleware' => 'can:manage-permission',
                ], static function () {
                    Route::get('/ability', [AbilityPresenter::class, 'renderList'])->name('ability.list');
                    Route::get('/ability/{ability}', [AbilityPresenter::class, 'renderInfo'])->name('ability.info');
                    Route::patch('/ability/{ability}', [AccountApiController::class, 'updateAbility'])->name('ability.update.api');

                    Route::get('/role', [RolePresenter::class, 'renderList'])->name('role.list');
                    Route::get('/role/{role}', [RolePresenter::class, 'renderInfo'])->name('role.info');
                    Route::patch('/role/{role}', [AccountApiController::class, 'updateRole'])->name('role.update.api');

                    Route::patch('/{account}/permission', [AccountApiController::class, 'updatePermission'])->name('permission.update.api');
                });
            });

            Route::group([
                'as' => 'level.',
                'prefix' => 'level',
            ], static function () {
                Route::group([
                    'middleware' => 'can:rate-level',
                ], static function () {
                    Route::get('/{level}/rate', [LevelPresenter::class, 'renderRate'])->name('rate');
                    Route::post('/{level}/rate', [LevelApiController::class, 'rate'])->name('rate.api');
                });

                Route::group([
                    'middleware' => 'can:mark-level',
                ], static function () {
                    Route::post('/{level}/mark:daily', [LevelApiController::class, 'markAsDaily'])->name('mark.daily');
                    Route::post('/{level}/mark:weekly', [LevelApiController::class, 'markAsWeekly'])->name('mark.weekly');
                });
            });
        });

        Route::group([
            'prefix' => 'account',
            'as' => 'account.',
        ], static function () {
            Route::get('/verify', [AccountApiController::class, 'verify'])
                ->middleware('signed')
                ->name('verify');

            Route::get('/profile', [AccountPresenter::class, 'renderProfile'])->name('profile');
            Route::post('/resend:email_verification', [AccountApiController::class, 'resendEmailVerification'])->name('resendEmailVerification.api');
            Route::get('/setting', [AccountPresenter::class, 'renderSetting'])->name('setting');
            Route::patch('/setting', [AccountApiController::class, 'updateSetting'])->name('setting.update.api');
            Route::get('/failed-log', [AccountPresenter::class, 'renderFailedLogs'])->name('failed-log');
            Route::get('/logout', [AccountApiController::class, 'logout'])->name('logout.api');
            Route::delete('/failed-logs', [AccountFailedLogApiController::class, 'clear'])->name('failed-log.clear.api');
        });

        Route::group([
            'prefix' => 'dashboard',
            'as' => 'dashboard.',
        ], static function () {
            Route::get('/', [DashboardPresenter::class, 'renderHome'])->name('home');

            Route::get('/account/{account}', [InformationPresenter::class, 'renderAccount'])
                ->where('id', '\d+')
                ->name('account.info');

            Route::get('/level/{level}', [InformationPresenter::class, 'renderLevel'])
                ->where('id', '\d+')
                ->name('level.info');

            Route::patch('/level/{level}', [LevelApiController::class, 'update'])
                ->where('id', '\d+')
                ->name('level.update');

            Route::get('/user/{user}', [InformationPresenter::class, 'renderUser'])
                ->where('id', '\d+')
                ->name('user.info');
        });

        Route::group([
            'prefix' => 'tools',
            'as' => 'tools.',
        ], static function () {
            Route::inertia('/', 'GDCS/Tools/Home')->name('home');

            Route::group([
                'prefix' => 'account',
                'as' => 'account.',
            ], static function () {
                Route::group([
                    'prefix' => 'link',
                    'as' => 'link.',
                ], static function () {
                    Route::get('/', [AccountLinkPresenter::class, 'renderList'])->name('list');
                    Route::inertia('/create', 'GDCS/Tools/Account/Link/Create')->name('create');

                    Route::post('/create', [AccountLinkApiController::class, 'create'])->name('create.api');
                    Route::delete('/{link}', [AccountLinkApiController::class, 'delete'])
                        ->where('id', '\d+')
                        ->name('delete.api');
                });
            });

            Route::group([
                'prefix' => 'level',
                'as' => 'level.',
            ], static function () {
                Route::group([
                    'prefix' => 'tempUploadAccess',
                    'as' => 'temp_upload_access.',
                ], static function () {
                    Route::get('/', [LevelTempUploadAccessPresenter::class, 'list'])->name('list');

                    Route::get('/create', [LevelTempUploadAccessApiController::class, 'create'])->name('create.api');
                    Route::delete('/{access}', [LevelTempUploadAccessApiController::class, 'delete'])
                        ->where('id', '\d+')
                        ->name('delete.api');
                });

                Route::group([
                    'prefix' => 'transfer',
                    'as' => 'transfer.',
                ], static function () {
                    Route::inertia('/', 'GDCS/Tools/Level/Transfer/Home')->name('home');

                    Route::get('/in', [LevelTransferPresenter::class, 'renderTransferIn'])->name('in');
                    Route::post('/in', [LevelTransferApiController::class, 'transferIn'])->name('in.api');

                    Route::get('/out', [LevelTransferPresenter::class, 'renderTransferOut'])->name('out');
                    Route::post('/out', [LevelTransferApiController::class, 'transferOut'])->name('out.api');
                });
            });

            Route::group([
                'prefix' => 'song',
                'as' => 'song.',
            ], static function () {
                Route::group([
                    'prefix' => 'custom',
                    'as' => 'custom.',
                ], static function () {
                    Route::get('/', [CustomSongPresenter::class, 'renderList'])->name('list');

                    Route::inertia('/create:link', 'GDCS/Tools/Song/Custom/Create/Link')->name('create.link');
                    Route::post('/create:link', [CustomSongApiController::class, 'createLink'])->name('create.link.api');

                    Route::inertia('/create:netease', 'GDCS/Tools/Song/Custom/Create/Netease')->name('create.netease');
                    Route::post('/create:netease', [CustomSongApiController::class, 'createNetease'])->name('create.netease.api');

                    Route::delete('/{song}', [CustomSongApiController::class, 'delete'])
                        ->where('id', '\d+')
                        ->name('delete.api');
                });
            });
        });
    });

    Route::group([
        'as' => 'docs.',
        'prefix' => 'docs',
    ], static function () {
        Route::inertia('/', 'GDCS/Docs/Home')->name('home');

        Route::group([
            'as' => 'command.',
            'prefix' => 'command',
        ], static function () {
            Route::inertia('/account', 'GDCS/Docs/Command/Account')->name('account');
            Route::inertia('/level', 'GDCS/Docs/Command/Level')->name('level');
        });
    });
});

Route::group([
    'domain' => 'dl.geometrydashchinese.com',
    'as' => 'gdproxy.',
], static function () {
    Route::inertia('/', 'GDProxy/Home')->name('home');
});

Route::group([
    'domain' => 'ng.geometrydashchinese.com',
    'as' => 'ngproxy.',
], static function () {
    Route::inertia('/', 'NGProxy/Home')->name('home');

    Route::get('/{id}', [NGProxyPresenter::class, 'renderHomeWithSong'])
        ->where('id', '\d+')
        ->name('info');
});
