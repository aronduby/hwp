<?php

namespace App\Database\Schema\Grammars;

use Illuminate\Support\Fluent;

class MySqlGrammar extends \Illuminate\Database\Schema\Grammars\MySqlGrammar {

    /**
     * Create the column definition for an set type.
     *
     * @param  Fluent  $column
     * @return string
     */
    protected function typeSet(Fluent $column): string
    {
        return "set('".implode("', '", $column->allowed)."')";
    }

}
