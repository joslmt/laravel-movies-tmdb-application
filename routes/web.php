<?php

use App\Http\Controllers\MoviesController;
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

Route::get('/', [MoviesController::class, 'index'])->name('home');
Route::get('/seemore/{id?}', [MoviesController::class, 'seeMore'])->name('seemore');

Route::post('/store/{id?}/{title?}/{poster_path?}', [MoviesController::class, 'store'])->name('store')->middleware('auth');

Route::delete('/delete/{id?}', [MoviesController::class, 'destroy'])->name('delete')->middleware('auth');

require __DIR__ . '/auth.php';


Route::get('/profile', [MoviesController::class, 'profile'])->name('profile')->middleware('auth');
Route::post('/description', [MoviesController::class, 'description'])->name('description')->middleware('auth');

Route::view('/search', 'components.movieweb.general.resultsfound')->name('search');
