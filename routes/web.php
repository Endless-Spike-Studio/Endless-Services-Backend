<?php

use Illuminate\Support\Facades\Route;

Route::group(['domain' => 'gf.geometrydashchinese.com', 'as' => 'gdcs.'], static function () {
    Route::inertia('/', 'GDCS/Home')->name('home');
});
