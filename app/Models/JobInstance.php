<?php

namespace App\Models;

use App\Models\Traits\HasSettings;
use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Torzer\Awesome\Landlord\BelongsToTenants;

/**
 * Class JobSetting
 *
 * @package App\Models
 * @mixin Eloquent
 * @property int id
 * @property int $site_id
 * @property string $job
 * @property bool $enabled
 * @property string $settings
 * @property Carbon|null $last_ran
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Site $site
 * @method static Builder|JobInstance whereCreatedAt($value)
 * @method static Builder|JobInstance whereEnabled($value)
 * @method static Builder|JobInstance whereId($value)
 * @method static Builder|JobInstance whereJob($value)
 * @method static Builder|JobInstance whereSettings($value)
 * @method static Builder|JobInstance whereSiteId($value)
 * @method static Builder|JobInstance whereUpdatedAt($value)
 * @property-read Collection|JobLog[] $logs
 * @method static Builder|JobInstance whereLastRan($value)
 */
class JobInstance extends Model
{
    use BelongsToTenants,
        HasSettings;

    /**
     * Specify the tenant columns to use for this model
     * This always ignores the season tenant check
     *
     * @var array
     */
    public $tenantColumns = ['site_id'];

    /**
     * Cast the settings field to an array
     * @var string[]
     */
    protected $casts = [
        'last_ran' => 'datetime'
    ];

    /**
     * Has related logs
     *
     * @return HasMany
     */
    public function logs(): HasMany
    {
        return $this->hasMany('App\Models\JobLog');
    }

    /**
     * Belongs to a site
     *
     * @return BelongsTo
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo('App\Models\Site');
    }

    /**
     * Runs the console command that expects this instance
     * @return int - the exitCode
     */
    public function runCommand(): int
    {
        return call_user_func([$this->job, 'runCommand'], $this);
    }

}
