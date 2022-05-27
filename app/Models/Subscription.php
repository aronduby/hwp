<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Torzer\Awesome\Landlord\BelongsToTenants;

/**
 * Class Subscription
 *
 * @package App\Models
 * @property int $id
 * @property int $site_id
 * @property string $phone
 * @property string $type -- see the type constants below
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 */
class Subscription extends Model
{

    use BelongsToTenants;

    /**
     * Specify the tenant columns to use for this model
     * Subscriptions are always just per site, not the season
     *
     * @var string[]
     */
    public $tenantColumns = ['site_id'];

    /**
     * The attributes that are mass assignable
     *
     * @var string[]
     */
    protected $fillable = ['phone', 'type'];

    public function site() {
        return $this->belongsTo('App\Models\Site');
    }

    /**
     * Constants use for the type field
     */
    const TYPE_ALL = 'ALL';
    const TYPE_QUARTERS = 'QUARTERS';
    const TYPE_FINAL = 'FINAL';
}
