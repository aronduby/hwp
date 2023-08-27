<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameShutterfly extends Migration
{
    const MIGRATE = [
        'photos' => [
            'shutterfly_id' => 'media_id'
        ],
        'albums' => [
            'shutterfly_id' => 'media_id'
        ],
        'player_season' => [
            'shutterfly_tag' => 'media_tag'
        ]
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (self::MIGRATE as $table => $fields) {
            Schema::table($table, function (Blueprint $table) use ($fields) {
                foreach ($fields as $oldFieldName => $newFieldName) {
                    $table->renameColumn($oldFieldName, $newFieldName);
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach (self::MIGRATE as $table => $fields) {
            Schema::table($table, function (Blueprint $table) use ($fields) {
                foreach ($fields as $oldFieldName => $newFieldName) {
                    $table->renameColumn($newFieldName, $oldFieldName);
                }
            });
        }
    }
}
