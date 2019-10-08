<?php

namespace App\Models;

use Torzer\Awesome\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;

class PhotoAlbum extends Model
{
    use BelongsToTenants;
    
    protected $table = 'albums';

    public function cover()
    {
        return $this->belongsTo('App\Models\Photo');
    }

    public function photos()
    {
        return $this->belongsToMany('App\Models\Photo', 'album_photo', 'album_id');
    }

    public function game()
    {
        return $this->hasOne('App\Models\Game', 'album_id');
    }

    public function games()
    {
        return $this->hasMany('App\Models\Game', 'album_id');
    }
}
