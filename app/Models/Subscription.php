<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use NunoMazer\Samehouse\BelongsToTenants;

/**
 * Class Subscription
 *
 * @package App\Models
 * @property int $id
 * @property int $site_id
 * @property string $phone
 * @property string $type -- see the type constants below
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Site $site
 * @method static Builder|Subscription whereCreatedAt($value)
 * @method static Builder|Subscription whereId($value)
 * @method static Builder|Subscription wherePhone($value)
 * @method static Builder|Subscription whereSiteId($value)
 * @method static Builder|Subscription whereType($value)
 * @method static Builder|Subscription whereUpdatedAt($value)
 */
#[Fillable('phone', 'type')]
class Subscription extends Model
{

    use BelongsToTenants;

    /**
     * Specify the tenant columns to use for this model
     * Subscriptions are always just per site, not the season
     *
     * @var string[]
     */
    public array $tenantColumns = ['site_id'];

    public function site(): BelongsTo
    {
        return $this->belongsTo('App\Models\Site');
    }

    /**
     * Constants use for the type field
     */
    const string TYPE_ALL = 'ALL';
    const string TYPE_QUARTERS = 'QUARTERS';
    const string TYPE_FINAL = 'FINAL';
}
