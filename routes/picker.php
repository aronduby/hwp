<?php

use App\Http\Controllers\PickerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Picker Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all the routes for the picker state
|
*/

Route::get('/', [PickerController::class, 'index'])->name('picker');

Route::get('schedule/subscribe', function() {
    $tld = config('app.env') === 'local' ? 'local' : 'com';
    return redirect()->away('https://hudsonvillewaterpolo.'.$tld.'/schedule/subscribe');
});

Route::get('ical.ics', function() {
    $tld = config('app.env') === 'local' ? 'local' : 'com';
    return redirect()->away('https://hudsonvillewaterpolo.'.$tld.'/ical.ics');
});
