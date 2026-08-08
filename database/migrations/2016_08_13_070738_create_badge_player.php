<?php

use App\Database\Schema\Blueprint;
use App\Database\Migrations\Migration;

class CreateBadgePlayer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        $this->schema->create('badge_player', function (Blueprint $table) {
            $table->increments('id');
            $table->site();
            $table->player();
            $table->season();
            $table->integer('badge_id')->unsigned();
            $table->timestamps();

            $table->foreign('badge_id')
                ->references('id')->on('badges')
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
        $this->schema->drop('badge_player');
    }
}
