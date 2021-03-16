<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Note
 *
 * @property int $id
 * @property int $site_id
 * @property int $season_id
 * @property string $title
 * @property string $content
 * @property string|null $photo
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Note whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Note whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Note whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Note wherePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Note whereSeasonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Note whereSiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Note whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Note whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Note extends Model
{
    //
}
