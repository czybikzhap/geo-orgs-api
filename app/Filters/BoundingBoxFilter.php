<?php

namespace App\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class BoundingBoxFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        if ($value !== 'true') {
            return;
        }

        $minLat = (float) request('min_lat');
        $maxLat = (float) request('max_lat');
        $minLng = (float) request('min_lng');
        $maxLng = (float) request('max_lng');

        $query->join('buildings', 'organizations.building_id', '=', 'buildings.id')
            ->whereBetween('buildings.latitude', [$minLat, $maxLat])
            ->whereBetween('buildings.longitude', [$minLng, $maxLng]);
    }
}
