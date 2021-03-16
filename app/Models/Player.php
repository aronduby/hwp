<?php

namespace App\Models;

use App\Models\Contracts\Shareable;
use App\Models\Traits\HasStats;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Torzer\Awesome\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Player
 *
 * @package App\Models
 * @property string name_key
 * @property string first_name
 * @property string last_name
 * @property mixed pronouns
 * @property int $id
 * @property int $site_id
 * @property string $first_name
 * @property string $last_name
 * @property string $pronouns
 * @property string $name_key
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\PlayerSeason $activeSeason
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Article[] $articles
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Badge[] $badges
 * @property-read mixed $name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Photo[] $photos
 * @property-read \App\Collections\CustomCollection|\App\Models\PlayerSeason[] $seasons
 * @property-read \App\Collections\StatCollection|\App\Models\Stat[] $stats
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Player nameKey($nameKey)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Player whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Player whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Player whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Player whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Player whereNameKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Player wherePronouns($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Player whereSiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Player whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Player extends Model implements Shareable
{
    use BelongsToTenants, HasStats;

    /**
     * Specify the tenant columns to use for this model
     * This always ignores the season tenant check
     *
     * @var array
     */
    public $tenantColumns = ['site_id'];

    protected $_pronouns;

    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function scopeNameKey(Builder $query, $nameKey)
    {
        return $query->where('name_key', '=', $nameKey);
    }

    public function getRouteKeyName()
    {
        return 'name_key';
    }

    public function getPronouns($type = null) {
        if ($type) {
            return __('pronouns.' . $this->pronouns . '.' . $type);
        } else {
            return __('pronouns.' . $this->pronouns);
        }
    }

    public function pn() {
        if (!$this->_pronouns) {
            $this->_pronouns = (object) $this->getPronouns();
        }

        return $this->_pronouns;
    }

    public function articles()
    {
        return $this->belongsToMany('App\Models\Article')
            ->withPivot('highlight');
    }

    /**
     * Gets the badge relationship.
     * NOTE - this is not tenanted to the season, this get's everything
     *
     * @return BelongsToMany
     */
    public function badges()
    {
        return $this->belongsToMany('App\Models\Badge');
    }

    public function photos()
    {
        return $this->belongsToMany('App\Models\Photo');
    }

    public function seasons()
    {
        return $this->hasMany('App\Models\PlayerSeason')->with('season');
    }

    public function activeSeason()
    {
        $activeSeasonId = app('App\Models\ActiveSeason')->id;
        return $this->hasOne('App\Models\PlayerSeason')
            ->with('season')
            ->where('season_id', $activeSeasonId);
    }

    public function stats()
    {
        return $this->hasMany('App\Models\Stat');
    }

    public function isShareable()
    {
        // should probably do something to limit staff somehow?
        return true;
    }

    public function getShareableUrl()
    {
        return route('shareables.player', [
            'shape' => Shareable::SQUARE,
            'ext' => '.svg',
            'namekey' => $this->name_key
        ]);
    }
}
