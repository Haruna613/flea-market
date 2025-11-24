<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileSettingController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth','verified')->group(function () {
    Route::get('/', [ItemController::class, 'index'])->name('top');
    Route::get('/mypage', [ProfileSettingController::class, 'show'])->name('profile.settings.show');
    Route::post('/mypage', [ProfileSettingController::class, 'update'])->name('profile.settings.update');
});