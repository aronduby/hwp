<?php


namespace App\Jobs\Traits;


use App\Jobs\Contracts\ILoggable;
use App\Models\JobLog;
use App\Models\JobInstance;

/**
 * Class Loggable
 * @package App\Jobs\Traits
 *
 * @property JobInstance settings
 */
trait Loggable
{

    public function createLogObject(): JobLog
    {
        $log = new JobLog();
        $log->job_setting_id = $this->settings->id;
        $log->save();

        return $log;
    }

    public function getLogObject(): JobLog
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->settings->logs->first();
    }

    public function logStateChange(string $newState): JobLog
    {
        $log = $this->getLogObject();
        $log->state = $newState;
        $log->save();

        return $log;
    }

    public function logData($data): JobLog
    {
        $log = $this->getLogObject();
        $log->data = $data;
        $log->save();

        return $log;
    }
}