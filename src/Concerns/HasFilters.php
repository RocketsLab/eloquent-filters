<?php
/**
 * Created by PhpStorm.
 * User: Jorge
 * Date: 08/07/2017
 * Time: 11:50
 */

namespace Duuany\EloquentFilters\Concerns;

trait HasFilters
{
    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Duuany\EloquentFilters\EloquentFilters $filters
     * @return mixed
     */
    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }
}