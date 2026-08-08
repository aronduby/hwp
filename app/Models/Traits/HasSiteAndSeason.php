<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasSiteAndSeason
{

    public function season(): BelongsTo
    {
        return $this->belongsTo('App\Models\Season');
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo('App\Models\Site');
    }
}
