<?php
use Illuminate\Http\Request;

/**
* Shutterfly Extension Calls
*
*/
Route::group([
    'prefix' => 'shutterfly/',
    'namespace' => 'Shutterfly',
], function() {

    Route::get('players', ['uses' => 'ShutterflyController@listPlayers', 'as' => 'shutterfly.players.list']);

    Route::post('sync', ['uses' => 'ShutterflyController@syncPlayers', 'as' => 'shutterfly.players.sync']);

    Route::post('saveTags', ['uses' => 'ShutterflyController@saveTags', 'as' => 'shutterfly.tags.save']);

});