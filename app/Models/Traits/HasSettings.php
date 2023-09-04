<?php

namespace App\Models\Traits;

use App\Models\Settings;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasSettings
{
    public function settings(): MorphOne
    {
        return $this->morphOne(Settings::class, 'has_settings');
    }
}