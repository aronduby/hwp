<?php


namespace App\Jobs\Traits;


use App\Models\JobInstance;
use Illuminate\Support\Facades\Artisan;

trait HasJobInstance
{

    /**
     * @var JobInstance
     */
    protected $jobInstance ;

    /**
     * Create a new job instance.
     *
     * @param JobInstance $jobInstance
     */
    public function __construct(JobInstance $jobInstance)
    {
        $this->jobInstance = $jobInstance;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        static::runCommand($this->jobInstance);
    }

    /**
     * Runs the associated command with the given instance
     *
     * @param JobInstance $jobInstance
     * @return int
     */
    static function runCommand(JobInstance $jobInstance): int
    {
        return Artisan::call(self::$commandString, [
            'instanceId' => $jobInstance->id
        ]);
    }

}