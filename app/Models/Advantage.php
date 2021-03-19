<?php

namespace App\Models;

use Torzer\Awesome\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Advantage
 *
 * @property int $id
 * @property int $site_id
 * @property int $game_id
 * @property string $team
 * @property int $drawn
 * @property int $converted
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Game $game
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Advantage whereConverted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Advantage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Advantage whereDrawn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Advantage whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Advantage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Advantage whereSiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Advantage whereTeam($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Advantage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
