<?php

namespace App\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class BoundingBoxFilter implements Filter
{
    /**
     * $value ожидается как массив:
     * [
     *   'min_lat' => float,
     *   'max_lat' => float,
     *   'min_lng' => float,
     *   'max_lng' => float,
     * ]
     */
    public function __invoke(Builder $query, $value, string $property)
    {
        // Проверка что пришёл массив с координатами
        if (!is_array($value)) {
            return;
        }

        $minLat = $value['min_lat'] ?? null;
        $maxLat = $value['max_lat'] ?? null;
        $minLng = $value['min_lng'] ?? null;
        $maxLng = $value['max_lng'] ?? null;

        if ($minLat !== null && $maxLat !== null && $minLng !== null && $maxLng !== null) {
            $query->whereHas('building', function ($q) use ($minLat, $maxLat, $minLng, $maxLng) {
                $q->whereBetween('latitude', [$minLat, $maxLat])
                    ->whereBetween('longitude', [$minLng, $maxLng]);
            });
        }
    }
}
