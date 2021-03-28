<?php


namespace App\Models\Traits;


trait HasSiteAndSeason
{

    public function season() {
        return $this->belongsTo('App\Models\Season');
    }

    public function site() {
        return $this->belongsTo('App\Models\Site');
    }
}