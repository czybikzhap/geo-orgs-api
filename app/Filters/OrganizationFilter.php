<?php

namespace App\Filters;

use App\Models\Activity;
use Illuminate\Database\Eloquent\Builder;

class OrganizationFilter
{
    public function apply(Builder $query, array $filters): Builder
    {
        if (!empty($filters['name'])) {
            $query->where('name', 'ILIKE', "%{$filters['name']}%");
        }

        if (!empty($filters['building_id'])) {
            $query->where('building_id', $filters['building_id']);
        }

        if (!empty($filters['activity_id'])) {
            $allIds = [];
            foreach ($filters['activity_id'] as $id) {
                if (!empty($filters['include_descendants'])) {
                    $allIds = array_merge($allIds, $this->getDescendantIds((int)$id));
                } else {
                    $allIds[] = (int)$id;
                }
            }

            $query->whereHas('activities', fn($q) => $q->whereIn('activities.id', $allIds));
        }

        if (isset($filters['latitude'], $filters['longitude'], $filters['radius'])) {
            $this->applyRadiusFilter(
                $query,
                (float)$filters['latitude'],
                (float)$filters['longitude'],
                (float)$filters['radius']
            );
        }

        if (isset($filters['min_lat'], $filters['max_lat'], $filters['min_lng'], $filters['max_lng'])) {
            $query->whereHas('building', function ($q) use ($filters) {
                $q->whereBetween('latitude', [(float)$filters['min_lat'], (float)$filters['max_lat']])
                    ->whereBetween('longitude', [(float)$filters['min_lng'], (float)$filters['max_lng']]);
            });
        }

        return $query;
    }

    private function getDescendantIds(int $activityId): array
    {
        $ids = [$activityId];

        $children = Activity::where('parent_id', $activityId)->pluck('id')->toArray();

        foreach ($children as $childId) {
            $ids = array_merge($ids, $this->getDescendantIds($childId));
        }

        return $ids;
    }

    private function applyRadiusFilter(Builder $query, float $lat, float $lng, float $radius): void
    {
        $haversine = "(6371 * acos(cos(radians($lat)) * cos(radians(buildings.latitude))
                     * cos(radians(buildings.longitude) - radians($lng))
                     + sin(radians($lat)) * sin(radians(buildings.latitude))))";

        $query->join('buildings', 'organizations.building_id', '=', 'buildings.id')
            ->whereRaw("$haversine <= ?", [$radius]);
    }


}
