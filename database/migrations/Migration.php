<?php

namespace App\Database\Migrations;

use App\Database\Schema\Blueprint;
use App\Database\Schema\Grammars\MySqlGrammar;
use Illuminate\Database\Schema\Builder;
use Illuminate\Support\Facades\DB;

class Migration extends \Illuminate\Database\Migrations\Migration
{
    /**
     * @var Builder
     */
    protected Builder $schema;

    public function __construct()
    {
        // set new grammar class
        DB::connection()->setSchemaGrammar(new MySqlGrammar());

        $this->schema = DB::connection()->getSchemaBuilder();

        $this->schema->blueprintResolver(function ($table, $callback) {
            return new Blueprint($table, $callback);
        });
    }
}
