<?php

namespace App\Models;

use App\Models\Scopes\RankingScope;
use App\Models\Traits\HasTotal;
use Torzer\Awesome\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Ranking extends Model
{
    use HasTotal, BelongsToTenants;

    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime'
    ];

    /**
     * The "booting" method of the model. Adds our ranking scope to always include the ranks
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new RankingScope);
    }


    public function scopeLatest(Builder $query)
    {
        return $query->take(1);
    }

    public function ranks()
    {
        return $this->hasMany('App\Models\Rank');
    }
}
