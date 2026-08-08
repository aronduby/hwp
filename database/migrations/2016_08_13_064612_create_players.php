<?php

use App\Database\Schema\Blueprint;
use App\Database\Migrations\Migration;

class CreatePlayers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        $this->schema->create('players', function (Blueprint $table) {
            $table->increments('id');
            $table->site();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('name_key');
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
        $this->schema->drop('players');
    }
}
