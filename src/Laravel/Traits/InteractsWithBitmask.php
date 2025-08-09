<?php

namespace Henzeb\Enumhancer\Laravel\Traits;

use Henzeb\Enumhancer\Helpers\Bitmasks\Bitmask;
use Illuminate\Database\Eloquent\Builder;


trait InteractsWithBitmask
{
    public function scopeWhereBitmask(Builder $query, string $column, Bitmask|int $value): void
    {
        if ($value instanceof Bitmask) {
            $value = $value->value();
        }

        if ($value === 0) {
            $query->where($column, 0);

            return;
        }

        $query->whereRaw("`$column` & ? = ?", [
            $value, $value
        ]);
    }

    public function scopeOrWhereBitmask(Builder $query, string $column, Bitmask|int $value): void
    {
        if ($value instanceof Bitmask) {
            $value = $value->value();
        }

        if ($value === 0) {
            $query->orWhere($column, 0);

            return;
        }


        $query->orWhereRaw("`$column` & ? = ?", [
            $value, $value
        ]);
    }
}
