<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdvantageGoalStats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('stats', function(Blueprint $table) {
           $table->integer('advantage_goals')
               ->nullable()
               ->after('shoot_out_allowed');

           $table->integer('advantage_goals_allowed')
               ->nullable()
               ->after('advantage_goals');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('stats', function(Blueprint $table) {
            $table->dropColumn(['advantage_goals', 'advantage_goals_allowed']);
        });
    }
}
