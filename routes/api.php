<?php

use App\Http\Controllers\FirebaseCloudMessaging\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(Subscription::class)
    ->prefix('fcm')
    ->group(function () {
        Route::post('subscribe', 'create')->name('pushSubscription.subscribe');
        Route::delete('subscribe', 'delete')->name('pushSubscription.unsubscribe');
    });
