<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Pronouns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('players', function(Blueprint $table) {
            $table->enum('pronouns', ['he', 'she', 'they'])
                ->default('they')
                ->after('last_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('players', function(Blueprint $table) {
            $table->dropColumn('pronouns');
        });
    }
}
