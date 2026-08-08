<?php

use App\Database\Schema\Blueprint;
use App\Database\Migrations\Migration;

class CreateAlbumPhoto extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        $this->schema->create('album_photo', function (Blueprint $table) {
            $table->increments('id');
            $table->site();
            $table->integer('album_id')->unsigned();
            $table->integer('photo_id')->unsigned();
            $table->timestamps();

            $table->foreign('album_id')
                ->references('id')->on('albums')
                ->onDelete('cascade')
                ->onUpdate('cascade');

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
        $this->schema->drop('album_photo');
    }
}
