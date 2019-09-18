<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGameAlbumFallback extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Update and create the views to include the fallback

        // new game album fallback view
        $createStatement = file_get_contents('./database/migrations/2019_09_18_062631_create_game_with_album_fallback_view.sql');
        DB::statement($createStatement);

        // updated schedule view
        $createStatement = file_get_contents('./database/migrations/2019_09_18_062631_create_schedule_view_tournament_albums_and_game_album_fallback.sql');
        DB::statement($createStatement);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop the new view and rollback changes to the old one (ie, run the old create or replace
        DB::statement('DROP VIEW game_with_album_fallback');

        $createStatement = file_get_contents('./database/migrations/2019_09_16_055405_create_schedule_view_tournament_albums.sql');
        DB::statement($createStatement);
    }
}
