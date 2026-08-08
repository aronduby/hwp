<?php

use App\Database\Schema\Blueprint;
use App\Database\Migrations\Migration;

class CreateGames extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        $this->schema->create('games', function (Blueprint $table) {
            $table->increments('id');
            $table->site();
            $table->season();
            $table->integer('tournament_id')->nullable()->unsigned();
            $table->integer('location_id')->nullable()->unsigned();
            $table->integer('album_id')->nullable()->unsigned();
            $table->set('team', ['V', 'JV']);
            $table->string('title_append')->nullable();
            $table->dateTime('start');
            $table->dateTime('end');
            $table->boolean('district')->default('0');
            $table->string('opponent');
            $table->integer('score_us')->unsigned()->nullable();
            $table->integer('score_them')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('tournament_id')
                ->references('id')->on('tournaments')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('location_id')
                ->references('id')->on('locations')
                ->onDelete('set null')
                ->onUpdate('cascade');

            $table->foreign('album_id')
                ->references('id')->on('albums')
                ->onDelete('set null')
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
        $this->schema->drop('games');
    }
}
