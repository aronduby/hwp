<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Thetispro\Setting\Facades\Setting;

class Site extends Model
{
    use Notifiable;

    /**
     * properties that should get type casted
     * @var string[]
     */
    protected $casts = [
        'is_picker' => 'boolean'
    ];
    
    /**
     * @var \Thetispro\Setting\Setting
     */
    protected $settings;

    public function scopeDomain($query, $domain)
    {
        return $query->where('domain', '=', $domain);
    }

    /**
     * @return \Thetispro\Setting\Setting
     */
    public function getSettingsAttribute()
    {
        if (!isset($this->settings)) {
            $this->settings = Setting::filename($this->getSettingFileName())->load();
        }

        return $this->settings;
    }

    protected function getSettingFileName()
    {
        return $this->domain . '.json';
    }

    public function seasons()
    {
        return $this->hasMany('App\Models\Season', 'site_id');
    }

    public function picker() {
        return $this->belongsTo('App\Models\Site', 'parent_id');
    }

    public function sites() {
        return $this->hasMany('App\Models\Site', 'parent_id');
    }

    public function featuredPhotos() {
        return $this->hasMany('App\Models\Photo', 'site_id')
            ->withoutGlobalScopes(['season_id', 'site_id'])
            ->where('featured', '=', true)
            ->orderBy('season_id', 'desc');
    }

    /**
     * Pull the twitter authorization from the site settings file
     *
     * @param $notification
     * @return array
     */
    public function routeNotificationForTwitter($notification)
    {
        return [
            config('services.twitter.consumer_key'), // TWITTER_CONSUMER_KEY
            config('services.twitter.consumer_secret'), // TWITTER_CONSUMER_SECRET
            $this->getSettingsAttribute()->get('twitter.accessToken'), // TWITTER_ACCESS_TOKEN
            $this->getSettingsAttribute()->get('twitter.accessTokenSecret') // TWITTER_ACCESS_SECRET
        ];
    }
}
