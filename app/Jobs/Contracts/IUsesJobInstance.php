<?php


namespace App\Jobs\Contracts;


use App\Models\JobInstance;

interface IUsesJobInstance
{

    static function runCommand(JobInstance $jobInstance);

}