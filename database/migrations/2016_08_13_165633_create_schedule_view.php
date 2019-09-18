<?php

use App\Database\Schema\Blueprint;
use App\Database\Migrations\Migration;

class CreateScheduleView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $createStatement = file_get_contents('./database/migrations/2016_08_13_165633_create_schedule_view.sql');
        DB::statement($createStatement);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW schedule');
    }
}
