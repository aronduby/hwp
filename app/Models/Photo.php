<?php

namespace App\Models;

use App\Models\Contracts\PhotoSource;
use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Torzer\Awesome\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Photo
 *
 * @property int $id
 * @property int|null $site_id
 * @property int|null $season_id
 * @property int $featured
 * @property string|null $media_id
 * @property string $file
 * @property int $width
 * @property int $height
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|PhotoAlbum[] $albums
 * @property-read mixed $photo
 * @property-read mixed $thumb
 * @property-read Collection|Player[] $players
 * @method static Builder|Photo whereCreatedAt($value)
 * @method static Builder|Photo whereFeatured($value)
 * @method static Builder|Photo whereFile($value)
 * @method static Builder|Photo whereHeight($value)
 * @method static Builder|Photo whereId($value)
 * @method static Builder|Photo whereSeasonId($value)
 * @method static Builder|Photo whereMediaId($value)
 * @method static Builder|Photo whereSiteId($value)
 * @method static Builder|Photo whereUpdatedAt($value)
 * @method static Builder|Photo whereWidth($value)
 * @mixin Eloquent
 */
class Photo extends Model implements PhotoSource
{
    use BelongsToTenants;

    public function getPhotoAttribute(): string
    {
        return config('urls.photos') . '/' . $this->file . '.jpg';
    }

    /** @noinspection PhpUnused */
    public function getThumbAttribute(): string
    {
        return config('urls.photos') . '/thumbs/' . $this->file . '.jpg';
    }

    /** @noinspection PhpUnused */
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

    public function albums(): BelongsToMany
    {
        return $this->belongsToMany('App\Models\PhotoAlbum', 'album_photo');
    }

    public function players(): BelongsToMany
    {
        return $this->belongsToMany('App\Models\Player');
    }
}
