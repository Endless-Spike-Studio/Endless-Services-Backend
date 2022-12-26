<?php

use App\Http\Controllers\GDCS\Web\AuthController;
use App\Http\Presenters\GDCS\HomePresenter;
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
            Route::get('/verify/{_}', [AuthController::class, 'verify'])->name('verify');
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
            Route::inertia('/', 'GDCS/Dashboard/Home')->name('home');

            Route::group([
                'prefix' => 'account',
                'as' => 'account.'
            ], static function () {
                Route::inertia('/profile', 'GDCS/Dashboard/AccountProfile')->name('profile');
            });
        });

        Route::group([
            'prefix' => 'tools',
            'as' => 'tools.'
        ], static function () {
            Route::inertia('/', 'GDCS/Tools/Home')->name('home');
        });
    });
});
