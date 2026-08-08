<?php

use App\Database\Schema\Blueprint;
use App\Database\Migrations\Migration;

class CreateNotes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        $this->schema->create('notes', function (Blueprint $table) {
            $table->increments('id');
            $table->site();
            $table->season();
            $table->string('title');
            $table->text('content');
            $table->string('photo')->nullable();
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
        $this->schema->drop('notes');
    }
}
