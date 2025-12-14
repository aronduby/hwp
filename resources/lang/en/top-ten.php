<?php

use App\Services\TopTenService;

return [

    /*
    |--------------------------------------------------------------------------
    | Top 10 Language Lines
    |--------------------------------------------------------------------------
    |
    | Strings for the Top 10 pages
    |
    */
    'Top' => 'Top',
    'Ten' => 'Ten',
    'TopTen' => 'Top Ten',

    'Rank' => 'Rank',
    'Name' => 'Name',
    'Season' => 'Season',
    'Value' => 'Val',


    // region Stats
    TopTenService::GOALS => 'Goals',
    TopTenService::SHOOTING_PERCENTAGE => 'Shooting Percentage',
    TopTenService::ASSISTS => 'Assists',
    TopTenService::STEALS => 'Steals',
    TopTenService::KICKOUTS_DRAWN => 'Kickouts Drawn',
    TopTenService::FIVE_METERS_DRAWN => '5 Meters Drawn',
    TopTenService::SPRINTS_WON => 'Sprints Won',
    TopTenService::SPRINT_PERCENTAGE => 'Sprint Percentage',
    TopTenService::SAVES => 'Saves',
    TopTenService::SAVE_PERCENTAGE => 'Save Percentage',
    // endregion

    // region Types
    TopTenService::CAREER => 'Career',
    TopTenService::SEASON => 'Season',
    TopTenService::PER_GAME => 'Per Game',
    // endregion Types

];