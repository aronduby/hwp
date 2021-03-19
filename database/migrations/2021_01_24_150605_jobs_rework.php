<?php

use App\Database\Schema\Blueprint;
use App\Database\Migrations\Migration;

class JobsRework extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema->create('job_instances', function(Blueprint $table) {
            $table->increments('id');
            $table->site();
            $table->string('job');
            $table->boolean('enabled');
            $table->json('settings')->nullable();
            $table->timestamp('last_ran')->nullable();
            $table->timestamps();
        });

        $this->schema->create('job_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('job_instance_id')->unsigned();
            $table->string('state')->nullable();
            $table->longText('output')->nullable();
            $table->timestamps();

            $table->foreign('job_instance_id')
                ->references('id')->on('job_instances')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema->drop('job_logs');
        $this->schema->drop('job_instances');
    }
}
