<?php

namespace App\Models;

use Torzer\Awesome\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Photo
 *
 * @property int $id
 * @property int|null $site_id
 * @property int|null $season_id
 * @property int $featured
 * @property string|null $shutterfly_id
 * @property string $file
 * @property int $width
 * @property int $height
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PhotoAlbum[] $albums
 * @property-read mixed $photo
 * @property-read mixed $thumb
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Player[] $players
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Photo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Photo whereFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Photo whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Photo whereHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Photo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Photo whereSeasonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Photo whereShutterflyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Photo whereSiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Photo whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Photo whereWidth($value)
 * @mixin \Eloquent
 */
class Photo extends Model
{
    use BelongsToTenants;

    public function getPhotoAttribute()
    {
        return config('urls.photos') . '/' . $this->file . '.jpg';
    }

    public function getThumbAttribute()
    {
        return config('urls.photos') . '/thumbs/' . $this->file . '.jpg';
    }

    public function getJSONData(Player $player = null)
    {
        $json = ['main'=>null, 'also'=>[]];
        $playersTemp = $this->players;

        if($player != null){
            $playersTemp = $playersTemp->reject(function($p) use($player) {
                return $p->id === $player->id;
            });

            $json['main'] = $player->toArray();
        }

        if($playersTemp->count()){
            $json['also'] = $playersTemp->toArray();
        }

        return json_encode($json);
    }

    public function albums()
    {
        return $this->belongsToMany('App\Models\PhotoAlbum', 'album_photo');
    }

    public function players()
    {
        return $this->belongsToMany('App\Models\Player');
    }
}
