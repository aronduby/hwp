<?php
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Picker Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for the picker state
|
*/

Route::get('/', ['uses' => 'PickerController@index', 'as' => 'picker']);

Route::get('schedule/subscribe', function() {
    $tld = config('app.env') === 'local' ? 'local' : 'com';
    return redirect()->away('https://guys.hudsonvillewaterpolo.'.$tld.'/schedule/subscribe');
});

Route::get('ical.ics', function() {
    $tld = config('app.env') === 'local' ? 'local' : 'com';
    return redirect()->away('https://guys.hudsonvillewaterpolo.'.$tld.'/ical.ics');
});