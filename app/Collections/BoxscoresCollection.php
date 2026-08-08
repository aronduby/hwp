<?php

namespace App\Collections;


use Illuminate\Database\Eloquent\Collection;

class BoxscoresCollection extends Collection
{

    const int MINIMUM_QUARTER = 4;

    public function getQuarters()
    {
        return max($this->max('quarter'), self::MINIMUM_QUARTER);
    }

    public function quarter($quarter): static
    {
        return $this->filter(function($item) use ($quarter) {
            return $item->quarter == $quarter;
        });
    }

    public function us(): static
    {
        return $this->filter(function($item) {
            return $item->team == 'US';
        });
    }

    public function them(): static
    {
        return $this->filter(function($item) {
            return $item->team == 'THEM';
        });
    }

}
