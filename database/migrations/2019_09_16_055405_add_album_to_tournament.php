<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAlbumToTournament extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tournaments', function(Blueprint $table) {
            $table->integer('album_id')->nullable()->unsigned();

            $table->foreign('album_id')
                ->references('id')->on('albums')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });

        $createStatement = file_get_contents('./database/migrations/create_schedule_view_tournament_albums.sql');
        DB::statement($createStatement);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tournaments', function(Blueprint $table) {
            $table->dropForeign(['album_id']);
            $table->dropColumn('album_id');
        });

        $createStatement = file_get_contents('./database/migrations/create_schedule_view.sql');
        DB::statement($createStatement);
    }
}
