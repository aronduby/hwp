<?php

use App\Database\Schema\Blueprint;
use App\Database\Migrations\Migration;

class AddSiteToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        $this->schema->table('users', function (Blueprint $table) {
            $table->integer('site_id')
                ->unsigned()
                ->after('id');

            $table->foreign('site_id')
                ->references('id')->on('sites')
                ->onDelete('cascade')
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
        $this->schema->table('users', function (Blueprint $table) {
            $table->dropColumn('site_id');
        });
    }
}
