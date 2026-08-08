<?php

use App\Database\Schema\Blueprint;
use App\Database\Migrations\Migration;

class CreateTournaments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        $this->schema->create('tournaments', function (Blueprint $table) {
            $table->increments('id');
            $table->site();
            $table->season();
            $table->integer('location_id')->unsigned();
            $table->set('team', ['V', 'JV']);
            $table->string('title');
            $table->date('start');
            $table->date('end');
            $table->text('note')->nullable();
            $table->string('result')->nullable();
            $table->timestamps();

            $table->foreign('location_id')
                ->references('id')->on('locations')
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
        $this->schema->drop('tournaments');
    }
}
