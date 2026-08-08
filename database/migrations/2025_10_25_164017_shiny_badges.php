<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ShinyBadges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('badges', function(Blueprint $table) {
           $table->boolean('shiny')
               ->default(false)
               ->after('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('badges', function(Blueprint $table) {
            $table->dropColumn('shiny');
        });
    }
}
