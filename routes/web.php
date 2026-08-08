<?php

use App\Http\Controllers\AlbumController;
use App\Http\Controllers\Cloudinary\Tags;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobsController;
use App\Http\Controllers\NotesController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ShareableController;
use App\Http\Controllers\StatController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\Twilio\CallController;
use App\Http\Controllers\Twilio\SMSController;
use App\Http\Middleware\Grounded;
use App\Http\Middleware\NotTopBanana;
use App\Http\Requests\Request;
use App\Models\ActiveSeason;
use Cloudinary\Cloudinary;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/recent', [HomeController::class, 'recent'])->name('recent');
Route::get('/rankings', [HomeController::class, 'rankings'])->name('rankings');

Route::get('/players', [PlayerController::class, 'playerList'])->name('playerlist');
Route::get('players/{player}', [PlayerController::class, 'player'])->name('players')
    ->middleware(Grounded::class, NotTopBanana::class);

Route::get('schedule', [ScheduleController::class, 'index'])->name('schedule');
Route::get('schedule/subscribe', [ScheduleController::class, 'subscribe'])->name('schedule.subscribe');
Route::get('ical.ics', function() {
    return redirect()->route('schedule.subscribe');
});

Route::get('game/{game}/recap', [GameController::class, 'recap'])->name('game.recap');
Route::get('game/{game}/photos', [GameController::class, 'photos'])->name('game.photos');
Route::controller(StatController::class)
    ->prefix('game/{game}/stats')
    ->name('game.')
    ->group(function () {
        Route::get('/', 'view')->name('stats');
        Route::get('/edit', 'edit')->name('stats.edit')->middleware('auth');
        Route::post('/edit', 'save')->name('stats.edit')->middleware('auth');
    });

Route::get('stats', [StatController::class, 'aggregateView'])->name('stats');

Route::get('photos', [AlbumController::class, 'index'])->name('albumlist');
Route::get('photos/{album}', [AlbumController::class, 'photos'])->name('album');

Route::get('tournaments/{tournament}', [TournamentController::class, 'tournament'])->name('tournament');
Route::get('tournaments/{tournament}/photos', [TournamentController::class, 'photos'])->name('tournament.photos');

Route::get('notes/{note}', [NotesController::class, 'note'])->name('notes');

Route::controller(GalleryController::class)
    ->prefix('gallery')
    ->name('gallery.')
    ->group(function () {
        Route::get('recent/{recent}', 'recent')->name('recent');
        Route::get('album/{album}', 'album')->name('album');
        Route::get('player/{player}', 'playerCareer')->name('playerCareer');
        Route::get('player/{player}/season/{season}', 'playerSeason')->name('playerSeason');
    });

Route::get('files', function() {
    return view('files', [
        'googleFolderID' => resolve('App\Models\ActiveSite')->settings->get('google.folder.id')
    ]);
})->name('files');
Route::redirect('/parents', '/files');

Route::controller(ShareableController::class)
    ->middleware(['cors'])
    ->prefix('shareables/{shape}')
    ->name('shareables.')
    ->group(function() {
        Route::get('game{ext}', 'game')->name('game');
        Route::get('player{ext}', 'player')->name('player');
        Route::get('update{ext}', 'update')->name('update');
    });

/*
 * Scavenger Hunt Related
 */
Route::get('step3', function() {
    return view('partials.scavenger.step3');
});

Route::get('shook', function() {
    return view('partials.scavenger.step4');
});

Route::post('shook', function(Request $request) {
    $answer = '85';

    $first = Emoji\is_single_emoji($request->input('first'));
    $second = Emoji\is_single_emoji($request->input('second'));
    $success = (
        $first !== false
        && $second !== false
        && $first['short_name'] === 'rolling_on_the_floor_laughing'
        && $second['short_name'] === 'doughnut'
    );

    return response()->json([
        'first' => $success ? $answer[0] : '' . rand(1,9),
        'second' => $success ? $answer[1] : '' . rand(1,9),
        'success' => $success,
        'help' => App::environment('local') ? ['first' => $first, 'second' => $second] : false
    ]);
});

Route::get('poltergeist', function() {
    return view('partials.scavenger.step6');
});

Route::get('111100011', function() {
    return view('partials.scavenger.final');
});

/**
 * Twilio Calls
 */
Route::prefix('twilio/incoming/')
    ->name('twilio.')
    ->group(function() {
        Route::controller(CallController::class)
            ->prefix('call')
            ->name('call.')
            ->group(function() {
                Route::get('welcome', 'welcome')->name('welcome');
                Route::get('user/lookup', 'userLookup')->name('user.lookup');
                Route::get('user/stats', 'userStats')->name('user.stats');
            });

        Route::post('sms', [SMSController::class, 'incoming'])->name('sms.incoming');
    });

/**
 * Admin Pages
 */
Route::prefix('admin')
    ->middleware(['auth'])
    ->group(function() {
        Route::get('jobs/{jobInstance}/run', [JobsController::class, 'run'])->name('jobs.run');
        Route::resource('jobs', JobsController::class)
            ->parameters(['jobs' => 'jobInstance'])
            ->only(['index', 'store', 'update', 'destroy']);
    });

// TODO -- this should probably be in the api routes
Route::post('tags', Tags::class)
    ->middleware(['cloudinary'])
    ->name('cloudinary.tags');

/**
 * This is just for testing and scratchpad for that stuff, eventually delete
 */
Route::get('cloudinary', function(ActiveSeason $season) {
    $settings = $season->settings->get('cloudinary');
    $cloudinary = new Cloudinary([
        'cloud' => [
            'cloud_name' => $settings['cloud_name'],
            'api_key' => $settings['api_key'],
            'api_secret' => $settings['api_secret'],
            'url' => [
                'secure' => true
            ]
        ]
    ]);

    // undocumented folder search, but I don't think we're going to need it because we're planning on importing those
    // $rootFolder = $settings['root_folder'];
    // $data = $cloudinary->searchFoldersApi()->expression('folder:"'.$rootFolder.'/*"')->execute();
    // $data = $cloudinary->adminApi()->subFolders($rootFolder);

    // list everything in a folder
    // $data = $cloudinary->searchApi()
    //     ->expression('folder:23-24/* && folder:"23-24/Test Subfolder"')
    //     ->withField('metadata')
    //     ->withField('tags')
    //     ->maxResults(MediaService::PER_PAGE)
    //     ->execute();

    // list everything with player tag
    // $data = $cloudinary->searchApi()
    //     ->expression('folder:"24-25/*" AND metadata.players=matthew_lawrence')
    //     ->maxResults(\App\Services\MediaServices\MediaService::PER_PAGE)
    //     ->execute();

    $player = \App\Models\Player::nameKey('MatthewLawrence')->firstOrFail();
    $playerDataService = new \App\Services\PlayerData\PlayerDataService($player, $season->id);
    $data = $playerDataService->getAllPhotos();

    // players field
    // $data = $cloudinary->adminApi()->metadataFieldByFieldId('players');

    return response()->json($data);
});
