<?php

use App\Database\Schema\Blueprint;
use App\Database\Migrations\Migration;

class CreateBoxscoreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        $this->schema->create('boxscores', function(Blueprint $table) {
            $table->increments('id');
            $table->site();
            $table->integer('game_id')->unsigned();
            $table->enum('team', ['US', 'THEM']);
            $table->tinyInteger('quarter')->unsigned();
            $table->timestamps();

            // set stupid defaults for the player and name so unique key will work
            $table->integer('player_id')->unsigned()->default(0);
            $table->string('name')->default('');

            $table->tinyInteger('goals')->unsigned()->default(0);

            $table->foreign('game_id')
                ->references('id')->on('games')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('player_id')
                ->references('id')->on('players')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unique(['game_id', 'quarter', 'player_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        $this->schema->drop('boxscores');
    }
}
