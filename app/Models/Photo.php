<?php

namespace App\Models;

use Torzer\Awesome\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use BelongsToTenants;

    public function getPhotoAttribute()
    {
        return config('urls.photos') . '/' . $this->file . '.jpg';
    }

    public function getThumbAttribute()
    {
        return config('urls.photos') . '/thumbs/' . $this->file . '.jpg';
    }

    public function getJSONData(Player $player = null)
    {
        $json = ['main'=>null, 'also'=>[]];
        $playersTemp = $this->players;

        if($player != null){
            $playersTemp = $playersTemp->reject(function($p) use($player) {
                return $p->id === $player->id;
            });

            $json['main'] = $player->toArray();
        }

        if($playersTemp->count()){
            $json['also'] = $playersTemp->toArray();
        }

        return json_encode($json);
    }

    public function albums()
    {
        return $this->belongsToMany('App\Models\PhotoAlbum', 'album_photo');
    }

    public function players()
    {
        return $this->belongsToMany('App\Models\Player');
    }
}
