<?php


namespace App\Models\Contracts;

interface IPersistTo
{

    /**
     * @return string - the name of the table to read from (should be the same as the default $table)
     */
    public function getReadTable(): string;

    /**
     *  @return string - the name of the table to write to
     */
    public function getWriteTable(): string;

    /**
     * Set the table associated with the model. Fulfilled by Model.
     *
     * @param  string  $table
     */
    public function setTable(string $table);

}
