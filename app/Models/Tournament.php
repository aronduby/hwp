<?php

namespace App\Models;

use App\Models\Traits\Event;
use HipsterJazzbo\Landlord\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Relation;

class Tournament extends Model
{
    use BelongsToTenant, Event;

    /**
     * Force start an end to be datetimes/carbon
     *
     * @var array
     */
    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime'
    ];

    public function getResultAttribute($value)
    {
        if ($value && strlen($value)) {
            return $value;
        } else {
            $results = [];
            $results[Game::WIN] = 0;
            $results[Game::LOSS] = 0;
            $results[Game::TIE] = 0;

            foreach($this->games as $game) {
                if (!$status = $game->status()) {
                    continue;
                }
                $results[$game->status()]++;
            }

            return preg_replace('/\-0$/', '', join('-', $results));
        }
    }

    public function getRecentTitleAttribute()
    {
        $title = trans('misc.'.$this->team) . ' ' . trans('misc.finished') . ' ' . $this->result;
        if (ends_with($this->title, 's')) {
            $title .= ' ' . trans('misc.at');
        } else {
            $title .= ' ' . trans('misc.atThe');
        }
        $title .= ' ' . $this->title;

        return $title;
    }

    public function location()
    {
        return $this->belongsTo('App\Models\Location');
    }

    public function games()
    {
        return $this->hasMany('App\Models\Game');
    }

    public function season()
    {
        return $this->belongsTo('App\Models\Season');
    }

    public function album()
    {
        return $this->belongsTo('App\Models\PhotoAlbum');
    }
}
