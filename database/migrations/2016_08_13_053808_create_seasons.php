<?php

use App\Database\Schema\Blueprint;
use App\Database\Migrations\Migration;

class CreateSeasons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        $this->schema->create('seasons', function (Blueprint $table) {
            $table->increments('id');
            $table->site();
            $table->string('title');
            $table->string('short_title');
            $table->boolean('current')->default('0');
            $table->string('ranking')->nullable();
            $table->timestamp('ranking_updated');
            $table->boolean('ranking_tie')->default('0');
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
        $this->schema->drop('seasons');
    }
}
