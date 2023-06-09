<?php

namespace App\Filters;

use App\Traits\Makeable;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

abstract class Filters implements FiltersContract
{
    use Makeable;

    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected array $allowedFilters = [];

    /**
     * Filters to apply.
     *
     * @var Collection
     */
    protected Collection $filters;

    /**
     * The Eloquent builder.
     *
     * @var Builder
     */
    protected Builder $builder;

    /**
     * Create a new filter instance.
     *
     * @param array $filters
     */
    public function __construct(array $filters = [])
    {
        $this->filters = collect($filters);
    }

    /**
     * Apply the filters.
     *
     * @param Builder $builder
     * @return Builder
     */
    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        foreach ($this->getFilters() as $filter => $value) {
            if (method_exists($this, $filter)) {
                $this->$filter($value);
            }
        }

        return $this->builder;
    }

    /**
     * Fetch all relevant filters from the request.
     *
     * @return Collection
     */
    private function getFilters(): Collection
    {
        return $this->filters->only($this->allowedFilters)
            ->filter(fn($value) => $this->notEmpty($value));
    }

    /**
     * Check if value is not empty or null.
     *
     * @param $value
     * @return bool
     */
    private function notEmpty($value): bool
    {
        return !is_null($value) && $value !== '';
    }
}
