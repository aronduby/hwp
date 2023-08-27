<?php

namespace App\Models;

use Torzer\Awesome\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PhotoAlbum
 *
 * @property int $id
 * @property int $site_id
 * @property int $season_id
 * @property string|null $media_id
 * @property int|null $cover_id
 * @property string $title
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Photo|null $cover
 * @property-read \App\Models\Game $game
 * @property-read \App\Collections\CustomCollection|\App\Models\Game[] $games
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Photo[] $photos
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PhotoAlbum whereCoverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PhotoAlbum whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PhotoAlbum whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PhotoAlbum whereSeasonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PhotoAlbum whereMediaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PhotoAlbum whereSiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PhotoAlbum whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PhotoAlbum whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PhotoAlbum extends Model
{
    use BelongsToTenants;
    
    protected $table = 'albums';

    public function cover()
    {
        return $this->belongsTo('App\Models\Photo');
    }

    public function photos()
    {
        return $this->belongsToMany('App\Models\Photo', 'album_photo', 'album_id');
    }

    public function game()
    {
        return $this->hasOne('App\Models\Game', 'album_id');
    }

    public function games()
    {
        return $this->hasMany('App\Models\Game', 'album_id');
    }
}
