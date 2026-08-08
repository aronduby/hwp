<?php

use App\Database\Schema\Blueprint;
use App\Database\Migrations\Migration;

class CreateRankingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        $this->schema->create('rankings', function(Blueprint $table) {
            $table->increments('id');
            $table->site();
            $table->season();
            $table->integer('rank')->nullable();
            $table->date('start');
            $table->date('end');
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
        $this->schema->drop('rankings');
    }
}
