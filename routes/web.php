<?php

use App\Http\Presenters\GDCS\HomePresenter;
use Illuminate\Support\Facades\Route;

Route::group([
    'domain' => 'gf.geometrydashchinese.com',
    'as' => 'gdcs.',
], static function () {
    Route::get('/', [HomePresenter::class, 'renderHome'])->name('home');
});
