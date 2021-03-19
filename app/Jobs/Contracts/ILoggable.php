<?php


namespace App\Jobs\Contracts;


interface ILoggable
{

    public function createLogObject();

    public function getLogObject();

    public function logStateChange(string $newState);

    public function logData($data);

}