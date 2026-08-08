<?php

use App\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateScheduleView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        $createStatement = file_get_contents('./database/migrations/2016_08_13_165633_create_schedule_view.sql');
        DB::statement($createStatement);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        DB::statement('DROP VIEW schedule');
    }
}
