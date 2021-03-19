<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPickerSites extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sites', function(Blueprint $table) {
            $table->boolean('is_picker')
                ->after('id')
                ->default(0);

            $table->integer('parent_id')
                ->nullable()
                ->unsigned()
                ->after('is_picker');

            $table->foreign('parent_id')
                ->references('id')->on('sites')
                ->onDelete('set null')
                ->onUpdate('cascade');

            $table->string('title', 100)
                ->after('domain');

            $table->string('subtitle', 100)
                ->nullable()
                ->after('title');

            $table->string('description', 255)
                ->after('subtitle')
                ->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['is_picker', 'parent_id', 'title', 'subtitle', 'description']);
        });
    }
}
