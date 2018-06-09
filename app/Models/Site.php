<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Thetispro\Setting\Facades\Setting;

class Site extends Model
{
    use Notifiable;

    
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
}
