<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Torzer\Awesome\Landlord\BelongsToTenants;

/**
 * App\Models\PushSubscription
 *
 * @property int $id
 * @property int $site_id
 * @property string $token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|PushSubscription whereCreatedAt($value)
 * @method static Builder|PushSubscription whereId($value)
 * @method static Builder|PushSubscription whereSiteId($value)
 * @method static Builder|PushSubscription whereToken($value)
 * @method static Builder|PushSubscription whereUpdatedAt($value)
 */
class PushSubscription extends Model
{
    use BelongsToTenants;

    public $tenantColumns = ['site_id'];

    protected $fillable = ['token', 'site_id'];
}
