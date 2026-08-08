<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGameBadge extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('games', function(Blueprint $table)
        {
            $table->integer('badge_id')->nullable()->unsigned()->after('album_id');

            $table->foreign('badge_id')
                ->references('id')->on('badges')
                ->onDelete('set null')
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
        Schema::table('games', function(Blueprint $table)
        {
            $table->dropColumn('badge_id');
        });
    }
}
