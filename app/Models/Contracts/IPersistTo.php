<?php


namespace App\Models\Contracts;

interface IPersistTo
{

    /**
     * @return string - the name of the table to read from (should be the same as the default $table)
     */
    public function getReadTable();

    /**
     *  @return string - the name of the table to write to
     */
    public function getWriteTable();

    /**
     * Set the table associated with the model. Fulfilled by Model.
     *
     * @param  string  $table
     * @return $this
     */
    public function setTable($table);

}