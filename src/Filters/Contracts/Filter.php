<?php

namespace Koch\Filters\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface Filter
{
    /**
     * Applies all available filters.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Builder $builder);

    /**
     * Returns all filters from the request.
     *
     * @return array
     */
    public function filters();
}
