<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\GameStatDump
 *
 * @property int $id
 * @property int $site_id
 * @property int $game_id
 * @property string|null $json
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Game $game
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GameStatDump whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GameStatDump whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GameStatDump whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GameStatDump whereJson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GameStatDump whereSiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GameStatDump whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class GameStatDump extends Model
{

    public function getJsonAttribute($val)
    {
        return json_decode($val, false);
    }

    public function game()
    {
        return $this->belongsTo('App\Models\Game');
    }
}
