<?php

namespace App\Models;

use Torzer\Awesome\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;

class Advantage extends Model
{
    use BelongsToTenants;

    /**
     * Specify the tenant columns to use for this model
     * This always ignores the season tenant check
     *
     * @var array
     */
    protected $tenantColumns = ['site_id'];

    /**
     * The fields which CAN NOT be mass assigned
     *
     * @var array
     */
    protected $guarded = ['site_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function game()
    {
        return $this->belongsTo('App\Models\Game');
    }

    /**
     * Return a custom collection when getting this item
     *
     * @param array $models
     * @return \App\Collections\AdvantagesCollection
     */
    public function newCollection(array $models = [])
    {
        return new \App\Collections\AdvantagesCollection($models);
    }
}
