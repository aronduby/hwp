<?php

use App\Services\MediaServices\ShutterflyMediaService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMediaServiceToSeason extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('seasons', function (Blueprint $table) {
            $table->string('media_service', 255)
                ->default(addslashes(ShutterflyMediaService::class))
                ->after('ranking_title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('seasons', function(Blueprint $table) {
           $table->dropColumn('media_service');
        });
    }
}
