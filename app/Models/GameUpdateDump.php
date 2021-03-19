<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\GameUpdateDump
 *
 * @property int $id
 * @property int $site_id
 * @property int $game_id
 * @property array $json
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Game $game
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GameUpdateDump whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GameUpdateDump whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GameUpdateDump whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GameUpdateDump whereJson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GameUpdateDump whereSiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GameUpdateDump whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class GameUpdateDump extends Model
{
    protected $casts = [
        'json' => 'array'
    ];

    public function game()
    {
        return $this->belongsTo('App\Models\Game');
    }
}
