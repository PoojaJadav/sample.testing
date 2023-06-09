<?php

namespace App\Traits;

trait HasFilters
{
    public function scopeFilter($builder, array $filters = [])
    {
        if (!isset(static::$filters) || !static::$filters) {
            return $builder;
        }

        return static::$filters::make($filters)->apply($builder);
    }
}
