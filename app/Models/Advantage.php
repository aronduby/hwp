<?php

namespace App\Models;

use App\Collections\AdvantagesCollection;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Unguarded;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use NunoMazer\Samehouse\BelongsToTenants;

/**
 * App\Models\Advantage
 *
 * @property int $id
 * @property int $site_id
 * @property int $game_id
 * @property string $team
 * @property int $drawn
 * @property int $converted
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Game $game
 * @method static Builder|Advantage whereConverted($value)
 * @method static Builder|Advantage whereCreatedAt($value)
 * @method static Builder|Advantage whereDrawn($value)
 * @method static Builder|Advantage whereGameId($value)
 * @method static Builder|Advantage whereId($value)
 * @method static Builder|Advantage whereSiteId($value)
 * @method static Builder|Advantage whereTeam($value)
 * @method static Builder|Advantage whereUpdatedAt($value)
 */
#[Unguarded]
class Advantage extends Model
{
    use BelongsToTenants;

    /**
     * Specify the tenant columns to use for this model
     * This always ignores the season tenant check
     *
     * @var string[]
     */
    protected array $tenantColumns = ['site_id'];

    /**
     * @return BelongsTo
     */
    public function game(): BelongsTo
    {
        return $this->belongsTo('App\Models\Game');
    }

    /**
     * Return a custom collection when getting this item
     *
     * @param array $models
     * @return AdvantagesCollection
     */
    public function newCollection(array $models = []): AdvantagesCollection
    {
        return new AdvantagesCollection($models);
    }
}
