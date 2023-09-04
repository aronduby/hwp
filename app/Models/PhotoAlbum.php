<?php

namespace App\Models;

use App\Collections\CustomCollection;
use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Photo|null $cover
 * @property-read Game $game
 * @property-read CustomCollection|Game[] $games
 * @property-read Collection|Photo[] $photos
 * @method static Builder|PhotoAlbum whereCoverId($value)
 * @method static Builder|PhotoAlbum whereCreatedAt($value)
 * @method static Builder|PhotoAlbum whereId($value)
 * @method static Builder|PhotoAlbum whereSeasonId($value)
 * @method static Builder|PhotoAlbum whereMediaId($value)
 * @method static Builder|PhotoAlbum whereSiteId($value)
 * @method static Builder|PhotoAlbum whereTitle($value)
 * @method static Builder|PhotoAlbum whereUpdatedAt($value)
 * @mixin Eloquent
 */
class PhotoAlbum extends Model
{
    use BelongsToTenants;
    
    protected $table = 'albums';

    public function cover(): BelongsTo
    {
        return $this->belongsTo('App\Models\Photo');
    }

    public function photos(): BelongsToMany
    {
        return $this->belongsToMany('App\Models\Photo', 'album_photo', 'album_id');
    }

    public function game(): HasOne
    {
        return $this->hasOne('App\Models\Game', 'album_id');
    }

    public function games(): HasMany
    {
        return $this->hasMany('App\Models\Game', 'album_id');
    }
}
