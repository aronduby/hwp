<?php

use App\Database\Schema\Blueprint;
use App\Database\Migrations\Migration;

class CreatePhotoPlayer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        $this->schema->create('photo_player', function (Blueprint $table) {
            $table->increments('id');
            $table->site();
            $table->player();
            $table->season();
            $table->integer('photo_id')->unsigned();
            $table->timestamps();

            $table->unique(['player_id', 'season_id', 'photo_id'], 'photo_player_unique');

            $table->foreign('photo_id')
                ->references('id')->on('photos')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        $this->schema->drop('photo_player');
    }
}
