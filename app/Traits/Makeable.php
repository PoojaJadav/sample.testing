<?php

namespace App\Traits;

trait Makeable
{
    /**
     * Create a new instance of static class.
     *
     * @param mixed ...$arguments
     * @return static
     */
    public static function make(...$arguments): static
    {
        return new static(...$arguments);
    }
}
