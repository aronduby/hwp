<?php

use App\Database\Schema\Blueprint;
use App\Database\Migrations\Migration;

class CreatePushSubscriptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema->create('push_subscriptions', function(Blueprint $table) {
            $table->increments('id');
            $table->site();
            $table->text('token');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema->drop('push_subscriptions');
    }
}
