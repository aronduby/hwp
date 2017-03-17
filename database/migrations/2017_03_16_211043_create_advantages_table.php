<?php

use Illuminate\Support\Facades\Schema;
use App\Database\Schema\Blueprint;
use App\Database\Migrations\Migration;

class CreateAdvantagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema->create('advantages', function(Blueprint $table) {
            $table->increments('id');
            $table->site();
            $table->integer('game_id')->unsigned();
            $table->enum('team', ['US', 'THEM']);
            $table->integer('drawn')->unsigned()->default('0');
            $table->integer('converted')->unsigned()->default('0');

            $table->foreign('game_id')
                ->references('id')->on('games')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unique(['game_id', 'team']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema->drop('advantages');
    }
}
