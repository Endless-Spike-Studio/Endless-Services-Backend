<?php

use App\Http\Presenters\GDCS\HomePresenter;
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
    'as' => 'gdcs.',
], static function () {
    Route::get('/', [HomePresenter::class, 'renderHome'])->name('home');
});
