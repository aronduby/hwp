<?php

use App\Database\Schema\Blueprint;
use App\Database\Migrations\Migration;

class CreatePhotos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        $this->schema->create('photos', function (Blueprint $table) {
            $table->increments('id');
            $table->site();
            $table->season();
            $table->string('shutterfly_id')->nullable();
            $table->string('file');
            $table->integer('width');
            $table->integer('height');
            $table->timestamps();

            $table->unique(['shutterfly_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        $this->schema->drop('photos');
    }
}
