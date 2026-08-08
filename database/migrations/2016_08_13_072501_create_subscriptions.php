<?php

use App\Database\Schema\Blueprint;
use App\Database\Migrations\Migration;

class CreateSubscriptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        $this->schema->create('subscriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->site();
            $table->integer('phone');
            $table->integer('game_id')->unsigned()->nullable();
            $table->integer('tournament_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('game_id')
                ->references('id')->on('games')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('tournament_id')
                ->references('id')->on('tournaments')
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
        $this->schema->drop('subscriptions');
    }
}
