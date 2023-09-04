<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{

    protected $casts = [
        'settings' => 'array',
    ];

    protected $fillable = ['settings'];

    /**
     * Get all the owning commentable models.
     */
    public function owner()
    {
        return $this->morphTo('has_settings');
    }

    public function get(string $path = null, $defaultValue = null)
    {
        return data_get($this->settings, $path, $defaultValue);
    }

    public function set(string $path, $val)
    {
        return data_set($this->settings, $path, $val);
    }
}
