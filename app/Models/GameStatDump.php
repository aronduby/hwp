<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\GameStatDump
 *
 * @property int $id
 * @property int $site_id
 * @property int $game_id
 * @property string|null $json
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Game $game
 * @method static Builder|GameStatDump whereCreatedAt($value)
 * @method static Builder|GameStatDump whereGameId($value)
 * @method static Builder|GameStatDump whereId($value)
 * @method static Builder|GameStatDump whereJson($value)
 * @method static Builder|GameStatDump whereSiteId($value)
 * @method static Builder|GameStatDump whereUpdatedAt($value)
 */
class GameStatDump extends Model
{

    /**
     * Not sure why this wasn't a cast, but it wasn't so we're doing normal upgrade to attribute accessor
     * TODO -- should this be cast instead of attribute?
     *
     * @return Attribute
     */
    protected function json(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, false),
        );
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo('App\Models\Game');
    }
}
