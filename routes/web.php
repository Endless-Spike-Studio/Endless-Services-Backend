<?php

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
    'domain' => 'gf.geometrydashchinese.com',
    'as' => 'game.',
], static function () {
    Route::inertia('/', 'Game/Home')->name('home');

    Route::group([
        'prefix' => 'dashboard',
        'as' => 'dashboard.'
    ], static function () {
        Route::inertia('/', 'Game/Dashboard/Home')->name('home');
    });

    Route::group([
        'prefix' => 'tools',
        'as' => 'tools.'
    ], static function () {
        Route::inertia('/', 'Game/Tools/Home')->name('home');
    });

    Route::inertia('/', 'Game/Home')->name('home');
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
});
