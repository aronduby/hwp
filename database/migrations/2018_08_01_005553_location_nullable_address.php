<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LocationNullableAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('locations', function(Blueprint $table) {
            $table->string('street')->nullable()->change();
            $table->string('city')->nullable()->change();
            $table->string('state', 2)->nullable()->change();
            $table->string('zipcode', 5)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('locations', function(Blueprint $table) {
            $table->string('street')->change();
            $table->string('city')->change();
            $table->string('state', 2)->change();
            $table->string('zipcode', 5)->change();
        });
    }
}
