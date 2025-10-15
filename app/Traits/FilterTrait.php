<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait FilterTrait
{
    /**
     * Scope a query to filter categories by name and status.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param array $filters
     */
    public function scopeFilter(Builder $builder, array $filters)
    {
        if ($name = $filters['name'] ?? false) {
            $builder->where('name', 'LIKE', "%{$name}%");
        }
        if ($status = $filters['status'] ?? false) {
            $builder->whereStatus($status);
        }
    }
}
