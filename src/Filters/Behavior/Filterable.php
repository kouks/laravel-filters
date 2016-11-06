<?php

namespace Koch\Filters\Behavior;

use Koch\Filters\Filter;

trait Filterable
{
    /**
     * Assings the filter scope to a given model.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\Filters\Filter $filter
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, Filter $filter)
    {
        return $filter->apply($query);
    }
}
