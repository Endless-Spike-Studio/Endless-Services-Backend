<?php

use App\Http\Controllers\GDCS\Web\AuthController;
use App\Http\Presenters\GDCS\HomePresenter;
use Illuminate\Support\Facades\Route;

Route::group([
    'domain' => 'gf.geometrydashchinese.com',
    'as' => 'gdcs.',
], static function () {
    Route::get('/', [HomePresenter::class, 'renderHome'])->name('home');

    Route::group([
        'as' => 'auth.',
        'prefix' => 'auth'
    ], static function () {
        Route::group([
            'middleware' => ['guest:gdcs']
        ], static function () {
            Route::inertia('/login', 'GDCS/Auth/Login')->name('login');
            Route::post('/login', [AuthController::class, 'login'])->name('login.api');
        });
        Route::group([
            'middleware' => ['auth:gdcs']
        ], static function () {
            Route::get('/logout', [AuthController::class, 'logout'])->name('logout.api');
        });
    });

    Route::group([
        'prefix' => 'dashboard',
        'as' => 'dashboard.',
        'middleware' => ['auth:gdcs']
    ], static function () {
        Route::inertia('/', 'GDCS/Tools/Home')->name('home');
    });

    Route::group([
        'prefix' => 'tools',
        'as' => 'tools.',
        'middleware' => ['auth:gdcs']
    ], static function () {
        Route::inertia('/', 'GDCS/Tools/Home')->name('home');
    });
});
