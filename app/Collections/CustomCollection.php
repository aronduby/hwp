<?php
/**
 * Created by PhpStorm.
 * User: Duby
 * Date: 8/16/2016
 * Time: 9:11 PM
 */

namespace App\Collections;

use Illuminate\Database\Eloquent\Collection;

class CustomCollection extends Collection
{

    /**
     * For use where the value you are grouping by is a comma separated list (like mysql set).
     * This will create clones for each of the csv so they are in the individual groups
     *
     * @param $groupBy
     * @param bool $preserveKeys
     * @return static
     */
    public function groupBySet($groupBy, $preserveKeys = false)
    {
        $mapped = $this->flatMap(function($item) use ($groupBy) {
            $val = $item->$groupBy;
            if (str_contains($val, ',')) {
                $parts = explode(',', $val);
                $cloned = [];
                foreach($parts as $part) {
                    $clone = clone $item;
                    $clone->$groupBy = $part;
                    $cloned[] = $clone;
                }
                return $cloned;
            } else {
                return [$item];
            }
        });

        return $mapped->groupBy($groupBy, $preserveKeys);
    }

    /**
     * Allows for grouping on a field that is a Datetime\Carbon instance by using the passed in filter
     *
     * @param string $groupBy The field to group on (needs to be Datetime\Carbon instance)
     * @param string $format The format string to use for grouping
     * @param bool $preserveKeys
     * @return static
     */
    public function groupByDate($groupBy, $format, $preserveKeys = false)
    {
        $groupKey = '__'.$groupBy;

        $this->each(function($item) use ($groupBy, $format, $groupKey) {
            $item->$groupKey = $item->$groupBy->format($format);
        });

        return $this->groupBy($groupKey, $preserveKeys);
    }

    /**
     * @param string|null $team
     * @return CustomCollection
     */
    public function sortByNumber(string $team = null)
    {
        return $this->sortBy(function($ps) use ($team) {
            return $ps->getNumber($team);
        });
    }

}