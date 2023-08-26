<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SubscriptionUpdates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscriptions', function(Blueprint $table) {
            #region Per Site - Not Per Game/Tournament
            // first drop the foreign key constraints
            $table->dropForeign(['game_id']);
            $table->dropForeign(['tournament_id']);

            // then drop the columns
            $table->dropColumn(['game_id', 'tournament_id']);
            #endregion

            #region Subscription Types
            $table->string('type')
                ->after('phone')
                ->default(\App\Models\Subscription::TYPE_QUARTERS);
            #endregion

            #region Proper Phone Format
            $table->string('phone', 15)->change();
            #endregion
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscriptions', function(Blueprint $table) {
            #region Per Site
            $table->integer('game_id')->unsigned()->nullable();
            $table->integer('tournament_id')->unsigned()->nullable();

            $table->foreign('game_id')
                ->references('id')->on('games')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('tournament_id')
                ->references('id')->on('tournaments')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            #endregion

            #region Subscription Types
            $table->dropColumn('type');
            #endregion

            #region Proper Phone Format
            $table->integer('phone');
            #endregion
        });
    }
}
