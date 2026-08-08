<?php

use App\Database\Schema\Blueprint;
use App\Database\Migrations\Migration;

class CreateLocations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        $this->schema->create('locations', function (Blueprint $table) {
            $table->increments('id');
            $table->site();
            $table->string('title');
            $table->string('title_short');
            $table->string('street');
            $table->string('city');
            $table->string('state', 2);
            $table->string('zipcode', 5);
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        $this->schema->drop('locations');
    }
}
