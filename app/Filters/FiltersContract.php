<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

interface FiltersContract
{
    /**
     * Apply filters.
     *
     * @param Builder $builder
     * @return Builder
     */
    public function apply(Builder $builder): Builder;
}
