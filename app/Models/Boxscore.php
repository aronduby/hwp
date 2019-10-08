<?php

namespace App\Models;

use App\Services\PlayerListService;
use Torzer\Awesome\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;

class Boxscore extends Model
{
    use BelongsToTenants;

    /**
     * @var PlayerListService
     */
    protected $playerListService;

    /**
     * @var Player
     */
    protected $player;

    /**
     * Specify the tenant columns to use for this model
     * This always ignores the season tenant check
     *
     * @var array
     */
    protected $tenantColumns = ['site_id'];

    /**
     * Attributes that aren't mass assignable
     * Doing site_id keeps everything in the proper tenanted location
     *
     * @var array
     */
    protected $guarded = ['site_id'];

    /**
     * Boxscore constructor.
     *
     * @param array $attributes
     */
    public function __construct($attributes = [])
    {
        parent::__construct($attributes);

        $this->playerListService = app('App\\Services\\PlayerListService');
    }

    public function getPlayerAttribute()
    {
        if (!$this->player && $this->player_id) {
            $this->player = $this->playerListService->getPlayerById($this->player_id);
        }

        return $this->player;
    }

    public function getNameAttribute($name)
    {
        if ($this->player_id) {
            if (!$this->player) {
                $this->getPlayerAttribute();
            }

            return $this->player->name;
        }

        return $name;
    }

    public function game()
    {
        return $this->belongsTo('App\Models\Game');
    }

    public function newCollection(array $models = [])
    {
        return new \App\Collections\BoxscoresCollection($models);
    }


}
