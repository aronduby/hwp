<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class JobLog
 *
 * @package App\Models
 * @mixin Eloquent
 * @property int $id
 * @property int $job_instance_id
 * @property string|null $state
 * @property string|null $output
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|JobLog whereCreatedAt($value)
 * @method static Builder|JobLog whereData($value)
 * @method static Builder|JobLog whereId($value)
 * @method static Builder|JobLog whereJobSettingId($value)
 * @method static Builder|JobLog whereState($value)
 * @method static Builder|JobLog whereUpdatedAt($value)
 * @method static Builder|JobLog whereJobInstanceId($value)
 */
class JobLog extends Model
{
    const SUCCESS = 'success';
    const WARNING = 'warning';
    const ERROR = 'error';
    const UNKNOWN = 'unknown';

    // note - not tenanted, should only be pulled per job
}
