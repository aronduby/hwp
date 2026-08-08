<?php

use App\Database\Schema\Blueprint;
use App\Database\Migrations\Migration;

class CreateArticlePlayer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        $this->schema->create('article_player', function (Blueprint $table) {
            $table->increments('id');
            $table->site();
            $table->player();
            $table->season();
            $table->integer('article_id')->unsigned();
            $table->text('highlight')->nullable();
            $table->timestamps();

            $table->foreign('article_id')
                ->references('id')->on('articles')
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
        $this->schema->drop('article_player');
    }
}
