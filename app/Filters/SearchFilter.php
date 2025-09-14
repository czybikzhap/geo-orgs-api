<?php

namespace App\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class SearchFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        if (empty($value)) {
            return;
        }

        $query->where(function ($q) use ($value) {
            $q->where('organizations.name', 'ilike', "%{$value}%")
                ->orWhereHas('activities', function ($q) use ($value) {
                    $q->where('name', 'ilike', "%{$value}%");
                });
        });
    }
}
