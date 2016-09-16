<?php

use App\Database\Schema\Blueprint;
use App\Database\Migrations\Migration;

class CreateGameUpdateDumps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema->create('game_update_dumps', function (Blueprint $table) {
            $table->increments('id');
            $table->site();
            $table->integer('game_id')->unsigned();
            $table->text('json')->nullable();
            $table->timestamps();

            $table->foreign('game_id')
                ->references('id')->on('games')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema->drop('game_update_dumps');
    }
}
