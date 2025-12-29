<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileSettingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\StripeWebhookController;
use Illuminate\Support\Facades\Auth;

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

Route::get('/', [ItemController::class, 'index'])->name('top');

Route::get('/item/{item_id}', [ItemController::class, 'showDetail'])->name('item.detail');

Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle'])->name('stripe.webhook');

Route::middleware(['auth'])->group(function ()
{
    Route::middleware('verified')->group(function () {
        Route::get('/mypage', [ProfileController::class, 'index'])->name('profile');

        Route::get('/sell', [ItemController::class, 'show'])->name('item.sell.show');

        Route::post('/items', [ItemController::class, 'store'])->name('item.store');

        Route::get('/mypage/profile', [ProfileSettingController::class, 'show'])->name('profile.settings.show');

        Route::post('/mypage/profile', [ProfileSettingController::class, 'update'])->name('profile.settings.update');

        Route::post('/items/{item}/like', [LikeController::class, 'toggle'])->name('item.like.toggle');

        Route::post('/items/{item}/comments', [CommentController::class, 'store'])->name('item.comment.store');

        Route::get('/purchase/{item_id}', [PurchaseController::class, 'showPurchase'])->name('item.purchase.show');

        Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'showPurchaseAddress'])->name('purchase.address.show');

        Route::post('/purchase/address/{item_id}', [PurchaseController::class, 'updatePurchaseAddress'])->name('purchase.address.update');

        Route::post('/items/{item}/create-checkout-session', [PurchaseController::class, 'createCheckoutSession'])
        ->name('purchase.create-checkout-session');
    });
});