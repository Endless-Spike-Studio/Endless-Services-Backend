<?php

use App\Http\Controllers\GDCS\Web\AccountController;
use App\Http\Controllers\GDCS\Web\AccountLinkToolController;
use App\Http\Controllers\GDCS\Web\AuthController;
use App\Http\Controllers\GDCS\Web\LevelTempUploadAccessToolController;
use App\Http\Presenters\GDCS\AccountLinkToolPresenter;
use App\Http\Presenters\GDCS\AccountPresenter;
use App\Http\Presenters\GDCS\HomePresenter;
use App\Http\Presenters\GDCS\LevelTempUploadAccessToolPresenter;
use Illuminate\Support\Facades\Route;

Route::group([
    'domain' => 'gf.geometrydashchinese.com',
    'as' => 'gdcs.'
], static function () {
    Route::get('/', [HomePresenter::class, 'render'])->name('home');

    Route::group([
        'prefix' => 'auth',
        'as' => 'auth.'
    ], static function () {
        Route::group([
            'middleware' => 'guest:gdcs'
        ], static function () {
            Route::inertia('/register', 'GDCS/Auth/Register')->name('register');
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
            'prefix' => 'account',
            'as' => 'account.'
        ], static function () {
            Route::get('/profile', [AccountPresenter::class, 'renderProfile'])->name('profile');
            Route::post('/resendVerificationEmail', [AccountController::class, 'resendVerificationEmail'])->name('resendVerificationEmail.api');
            Route::get('/{account}', [AccountPresenter::class, 'renderInfo'])->name('info');
        });

        Route::group([
            'prefix' => 'dashboard',
            'as' => 'dashboard.'
        ], static function () {
            Route::inertia('/', 'GDCS/Dashboard/Home')->name('home');
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
                    'prefix' => 'tempUploadAccess',
                    'as' => 'temp_upload_access.'
                ], static function () {
                    Route::get('/', [LevelTempUploadAccessToolPresenter::class, 'renderIndex'])->name('index');
                    Route::post('/create', [LevelTempUploadAccessToolController::class, 'create'])->name('create.api');
                    Route::delete('/{access}', [LevelTempUploadAccessToolController::class, 'delete'])->name('delete.api');
                });
            });
        });
    });
});
