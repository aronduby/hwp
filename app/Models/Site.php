<?php

namespace App\Models;

use App\Models\Traits\HasSettings;
use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;

/**
 * App\Models\Site
 *
 * @mixin Eloquent
 * @property int $id
 * @property bool $is_picker
 * @property int|null $parent_id
 * @property string $domain
 * @property string $title
 * @property string|null $subtitle
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Photo[] $featuredPhotos
 * @property-read Collection|JobInstance[] $jobs
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read Site|null $picker
 * @property-read Collection|Season[] $seasons
 * @property-read Collection|Site[] $sites
 * @property-read Settings $settings
 * @method static Builder|Site domain($domain)
 * @method static Builder|Site whereCreatedAt($value)
 * @method static Builder|Site whereDescription($value)
 * @method static Builder|Site whereDomain($value)
 * @method static Builder|Site whereId($value)
 * @method static Builder|Site whereIsPicker($value)
 * @method static Builder|Site whereParentId($value)
 * @method static Builder|Site whereSubtitle($value)
 * @method static Builder|Site whereTitle($value)
 * @method static Builder|Site whereUpdatedAt($value)
 */
class Site extends Model
{
    use Notifiable,
        HasSettings;

    /**
     * properties that should get type casted
     * @var string[]
     */
    protected $casts = [
        'is_picker' => 'boolean'
    ];

    /** @noinspection PhpUnused */
    public function scopeDomain($query, $domain)
    {
        return $query->where('domain', '=', $domain);
    }

    /**
     * @return HasMany
     */
    public function seasons(): HasMany
    {
        return $this->hasMany('App\Models\Season', 'site_id');
    }

    public function picker(): BelongsTo
    {
        return $this->belongsTo('App\Models\Site', 'parent_id');
    }

    public function sites(): HasMany
    {
        return $this->hasMany('App\Models\Site', 'parent_id');
    }

    /** @noinspection PhpUnused */
    public function featuredPhotos(): HasMany
    {
        return $this->hasMany('App\Models\Photo', 'site_id')
            ->withoutGlobalScopes(['season_id', 'site_id'])
            ->where('featured', '=', true)
            ->orderBy('season_id', 'desc');
    }

    public function jobs(): HasMany
    {
        return $this->hasMany('App\Models\JobInstance', 'site_id');
    }

    /**
     * Pull the twitter authorization from the site settings file
     *
     * @return array
     * @noinspection PhpUnused
     */
    public function routeNotificationForTwitter(): array
    {
        return [
            config('services.twitter.consumer_key'), // TWITTER_CONSUMER_KEY
            config('services.twitter.consumer_secret'), // TWITTER_CONSUMER_SECRET
            $this->settings->get('twitter.accessToken'), // TWITTER_ACCESS_TOKEN
            $this->settings->get('twitter.accessTokenSecret') // TWITTER_ACCESS_SECRET
        ];
    }

    /**
     * Sends the topic identifier for FCM Topic notifications
     *
     * @return string
     * @noinspection PhpUnused
     */
    public function routeNotificationForFCMTopic(): string
    {
        return 'topic.'.$this->id;
    }
}
