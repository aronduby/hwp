<?php

use App\Database\Schema\Blueprint;
use App\Database\Migrations\Migration;

class CreatePlayerSeason extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        $this->schema->create('player_season', function (Blueprint $table) {
            $table->increments('id');
            $table->site();
            $table->player();
            $table->season();
            $table->string('title')->nullable();
            $table->set('team', ['V', 'JV', 'STAFF']);
            $table->set('position', ['FIELD', 'GOALIE']);
            $table->string('number', 10)->nullable();
            $table->string('shutterfly_tag')->nullable();
            $table->integer('sort')->nullable();
            $table->timestamps();

            $table->unique(['player_id', 'season_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        $this->schema->drop('player_season');
    }
}
