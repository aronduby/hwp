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