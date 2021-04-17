<?php

namespace App\Models;

use App\Models\Traits\HasSiteAndSeason;
use Torzer\Awesome\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Article
 *
 * @property int $id
 * @property int $site_id
 * @property int $season_id
 * @property string $title
 * @property string $url
 * @property string|null $photo
 * @property string|null $description
 * @property \Carbon\Carbon $published
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Player[] $players
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article wherePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article wherePublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereSeasonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereSiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereUrl($value)
 * @mixin \Eloquent
 */
class Article extends Model
{
    use BelongsToTenants, HasSiteAndSeason;

    protected $casts = [
        'published' => 'datetime'
    ];

    protected $fillable = [
        'title',
        'url',
        'photo',
        'description',
        'published'
    ];


    public function players()
    {
        return $this->belongsToMany('App\Models\Player')
            ->withPivot('highlight')
            ->withTimestamps();
    }


}
