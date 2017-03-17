<?php

namespace App\Collections;


class StatCollection extends CustomCollection
{

    /**
     * Gets the player stats (currently all of them)
     *
     * @return StatCollection
     */
    public function players()
    {
        return $this;
    }

    /**
     * Gets the goalies from the stats
     * Currently you are considered a goalie for this game if you have saves or goals allowed
     *
     * @return StatCollection
     */
    public function goalies()
    {
        return $this->filter(function($stat) {
            return ($stat->saves > 0 || $stat->goals_allowed > 0) ||
                $stat->player->position == 'GOALIE';
        });
    }

}