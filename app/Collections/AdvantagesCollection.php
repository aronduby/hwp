<?php

namespace App\Collections;

use App\Models\Advantage;
use Illuminate\Database\Eloquent\Collection;

class AdvantagesCollection extends Collection
{

    public function us(): Advantage
    {
        /**
         * @var $item Advantage
         */
        $item = $this->first(function($item) {
            return $item->team == 'US';
        });

        return $item ?: new Advantage();
    }

    public function them(): Advantage
    {
        /**
         * @var $item Advantage
         */
        $item = $this->first(function($item) {
            return $item->team == 'THEM';
        });

        return $item ?: new Advantage();
    }

}
