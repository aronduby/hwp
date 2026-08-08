<?php

use App\Database\Schema\Blueprint;
use App\Database\Migrations\Migration;

class CreateRecent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        $this->schema->create('recent', function (Blueprint $table) {
            $table->increments('id');
            $table->site();
            $table->season();
            $table->enum('renderer', ['photos', 'articles', 'note', 'game', 'tournament']);
            $table->text('content');
            $table->boolean('sticky')->default('0');
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
        $this->schema->drop('recent');
    }
}
