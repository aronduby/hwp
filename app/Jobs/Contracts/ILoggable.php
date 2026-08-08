<?php


namespace App\Jobs\Contracts;


use App\Models\JobLog;

interface ILoggable
{

    public function createLogObject(): JobLog;

    public function getLogObject(): JobLog;

    public function logStateChange(string $newState): JobLog;

    public function logData($data): JobLog;

}
