<?php

# intentionally left blank for now

Route::group([
    'prefix' => 'fcm',
    'namespace' => 'FirebaseCloudMessaging'
], function() {
    Route::post('subscribe', ['uses' => 'Subscription@create', 'as' => 'pushSubscription.subscribe']);
    Route::delete('subscribe', ['uses' => 'Subscription@delete', 'as' => 'pushSubscription.unsubscribe']);
});