<?php

use App\Database\Schema\Blueprint;
use App\Database\Migrations\Migration;

class SettingsUniqueIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema->table('settings', function(Blueprint $table) {
            $table->unique(['has_settings_type', 'has_settings_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema->table('settings', function(Blueprint $table) {
            $table->dropUnique(['has_settings_type', 'has_settings_id']);
        });
    }
}
