<?php

use App\Database\Schema\Blueprint;
use App\Database\Migrations\Migration;

class CreateAlbums extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        $this->schema->create('albums', function (Blueprint $table) {
            $table->increments('id');
            $table->site();
            $table->season();
            $table->string('shutterfly_id')->nullable();
            $table->integer('cover_id')->unsigned()->nullable();
            $table->string('title');
            $table->timestamps();

            $table->unique(['shutterfly_id']);

            $table->foreign('cover_id')
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
        $this->schema->drop('albums');
    }
}
