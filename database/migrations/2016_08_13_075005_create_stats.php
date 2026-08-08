<?php

use App\Database\Schema\Blueprint;
use App\Database\Migrations\Migration;

class CreateStats extends Migration
{
    protected array $fields = [
        'goals',
        'shots',
        'assists',
        'steals',
        'turnovers',
        'blocks',
        'kickouts_drawn',
        'kickouts',
        'saves',
        'goals_allowed',
        'sprints_taken',
        'sprints_won',
        'five_meters_drawn',
        'five_meters_taken',
        'five_meters_made',
        'five_meters_called',
        'five_meters_taken_on',
        'five_meters_blocked',
        'five_meters_allowed',
        'shoot_out_taken',
        'shoot_out_made',
        'shoot_out_taken_on',
        'shoot_out_blocked',
        'shoot_out_allowed'
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        $this->schema->create('stats', function (Blueprint $table) {
            $table->increments('id');
            $table->site();
            $table->player();
            $table->season();
            $table->integer('game_id')->unsigned()->nullable();

            foreach($this->fields as $field) {
                $table->integer($field)->nullable();
            }

            $table->timestamps();

            $table->unique(['player_id', 'game_id']);

            $table->foreign('game_id')
                ->references('id')->on('games')
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
        $this->schema->drop('stats');
    }
}
