<?php

namespace App\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class RadiusFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        if ($value !== 'true' && $value !== true) {
            return;
        }

        $latitude = (float) request('latitude');
        $longitude = (float) request('longitude');
        $radius = (float) request('radius', 5);

        if (!$latitude || !$longitude) {
            return;
        }

        $haversine = "(6371 * acos(cos(radians($latitude))
                     * cos(radians(buildings.latitude))
                     * cos(radians(buildings.longitude) - radians($longitude))
                     + sin(radians($latitude)) * sin(radians(buildings.latitude))))";

        $query->join('buildings', 'organizations.building_id', '=', 'buildings.id')
            ->whereRaw("$haversine <= ?", [$radius])
            ->select('organizations.*') // важно!
            ->orderByRaw($haversine);
    }
}
