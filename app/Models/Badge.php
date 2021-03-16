<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Badge
 *
 * @property int $id
 * @property string $title
 * @property string $image
 * @property string|null $description
 * @property int|null $display_order
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Player[] $players
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Season[] $seasons
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Badge whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Badge whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Badge whereDisplayOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Badge whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Badge whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Badge whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Badge whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Badge extends Model
{
    public function players()
    {
        return $this->belongsToMany('App\Models\Player');
    }

    public function seasons()
    {
        return $this->belongsToMany('App\Models\Season');
    }

}
