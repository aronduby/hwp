<?php


namespace App\Console\Commands\Traits;


use App\Models\JobInstance;
use App\Models\JobLog;

trait HasJobInstance
{

    /**
     * @var JobInstance
     */
    protected $jobInstance;

    /**
     * @var JobLog
     */
    protected $jobLog;

    abstract function argument(string $key);

    /**
     * Cache the job instance
     *
     * @return JobInstance
     */
    protected function getJobInstance(): JobInstance
    {
        if (!isset($this->jobInstance)) {
            $id = $this->argument('instanceId');
            /** @noinspection PhpIncompatibleReturnTypeInspection */
            $this->jobInstance = JobInstance::findOrFail(intval($id));
        }

        return $this->jobInstance;
    }

    /**
     * Cached job log
     *
     * @return JobLog
     */
    protected function getJobLog(): JobLog
    {
        if (!isset($this->jobLog)) {
            $this->jobLog = new JobLog();
        }

        return $this->jobLog;
    }

    /**
     * Override line, which all of the other output methods direct to
     *
     * @param $string
     * @param null $style
     * @param null $verbosity
     */
    public function line($string, $style = null, $verbosity = null)
    {
        $this->addToLogOutput($style, $string);
        parent::line($string, $style, $verbosity);
    }

    private function addToLogOutput(string $type, string $data)
    {
        $this->getJobLog()->output .= "[" . strtoupper($type) . "] " . $data . "\n";
    }

}