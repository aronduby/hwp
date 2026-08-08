<?php
/**
 * Created by PhpStorm.
 * User: Duby
 * Date: 8/20/2016
 * Time: 5:50 PM
 */

namespace App\Models\Traits;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

trait HasTotal
{

    /**
     * Runs a COUNT(*) on the table. DO NOT TRY TO CHAIN AFTER! This runs the actual query.
     *
     * @param Builder $query
     * @return integer
     */
    public function scopeTotal(Builder $query): int
    {
        return $query->select([DB::Raw('COUNT(*) AS `total`')])
            ->first()
            ->total;
    }
}
