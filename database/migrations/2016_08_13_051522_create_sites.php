<?php

use App\Database\Schema\Blueprint;
use App\Database\Migrations\Migration;

class CreateSites extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema->create('sites', function (Blueprint $table) {
            $table->increments('id');
            $table->string('domain', 255);
            $table->timestamps();

            $table->unique('domain');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema->drop('sites');
    }
}
