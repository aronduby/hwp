<?php

# intentionally left blank for now

Route::group([
    'prefix' => 'fcm',
    'namespace' => 'FirebaseCloudMessaging'
], function() {
    Route::post('subscribe');
});