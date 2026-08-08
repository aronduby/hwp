<?php
/**
 * Created by PhpStorm.
 * User: Duby
 * Date: 8/18/2016
 * Time: 8:17 PM
 */

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class RankingScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param Builder $builder
     * @param Model $model
     * @return Builder
     */
    public function apply(Builder $builder, Model $model): Builder
    {
        return $builder->with(['ranks' => function($query) {
            $query->orderBy('rank', 'asc');
        }])
            ->orderBy('end', 'desc');
    }


}
