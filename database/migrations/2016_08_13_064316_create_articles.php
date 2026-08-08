<?php

use App\Database\Schema\Blueprint;
use App\Database\Migrations\Migration;

class CreateArticles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        $this->schema->create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->site();
            $table->season();
            $table->string('title');
            $table->string('url');
            $table->string('photo')->nullable();
            $table->text('description')->nullable();
            $table->timestamp('published')->nullable();
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
        $this->schema->drop('articles');
    }
}
