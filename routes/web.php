<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'domain' => 'gf.geometrydashchinese.com',
    'as' => 'gdcs.'
], static function () {
    Route::hybridly('/', 'GDCS/Home')->name('home');
});

Route::group([
    'domain' => 'dl.geometrydashchinese.com',
    'as' => 'gdproxy.'
], static function () {
    Route::hybridly('/', 'GDProxy/Home')->name('home');
});

Route::group([
    'domain' => 'ng.geometrydashchinese.com',
    'as' => 'ngproxy.'
], static function () {
    Route::hybridly('/', 'NGProxy/Home')->name('home');
});
