<?php

use App\Database\Schema\Blueprint;
use App\Database\Migrations\Migration;

class CreateRanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        $this->schema->create('ranks', function(Blueprint $table) {
            $table->increments('id');
            $table->site();
            $table->season();
            $table->integer('ranking_id')->unsigned();
            $table->integer('rank')->unsigned();
            $table->string('team');
            $table->boolean('tied')->default(0);
            $table->boolean('self')->default(0);

            $table->foreign('ranking_id')
                ->references('id')->on('rankings')
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
        $this->schema->drop('ranks');
    }
}
