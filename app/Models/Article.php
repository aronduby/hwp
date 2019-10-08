<?php

namespace App\Models;

use Torzer\Awesome\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use BelongsToTenants;

    protected $casts = [
        'published' => 'datetime'
    ];

    protected $fillable = [
        'title',
        'url',
        'photo',
        'description',
        'published'
    ];


    public function players()
    {
        return $this->belongsToMany('App\Models\Player')
            ->withPivot('highlight')
            ->withTimestamps();
    }


}
