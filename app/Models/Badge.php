<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\Models\Badge
 *
 * @property int $id
 * @property string $title
 * @property string $image
 * @property string|null $description
 * @property bool $shiny
 * @property int|null $display_order
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Player[] $players
 * @property-read Collection|Season[] $seasons
 * @method static Builder|Badge whereCreatedAt($value)
 * @method static Builder|Badge whereDescription($value)
 * @method static Builder|Badge whereDisplayOrder($value)
 * @method static Builder|Badge whereId($value)
 * @method static Builder|Badge whereImage($value)
 * @method static Builder|Badge whereTitle($value)
 * @method static Builder|Badge whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Badge extends Model
{
    protected $casts = [
        'shiny' => 'boolean',
    ];

    public function players(): BelongsToMany
    {
        return $this->belongsToMany('App\Models\Player');
    }

    public function seasons(): BelongsToMany
    {
        return $this->belongsToMany('App\Models\Season');
    }

}
