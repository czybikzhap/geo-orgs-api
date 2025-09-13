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
            $activityIds = is_array($filters['activity_id'])
                ? $filters['activity_id']
                : explode(',', $filters['activity_id']);

            $allIds = [];
            foreach ($activityIds as $id) {
                $allIds = array_merge($allIds, $this->getDescendantIds((int)$id));
            }

            $query->whereHas('activities', fn($q) => $q->whereIn('activities.id', $allIds));
        }

        if (!empty($filters['latitude']) && !empty($filters['longitude']) && !empty($filters['radius'])) {
            $this->applyRadiusFilter($query, $filters['latitude'], $filters['longitude'], $filters['radius']);
        }

        if (!empty($filters['min_lat']) && !empty($filters['max_lat']) &&
            !empty($filters['min_lng']) && !empty($filters['max_lng'])) {
            $query->whereHas('building', function ($q) use ($filters) {
                $q->whereBetween('latitude', [$filters['min_lat'], $filters['max_lat']])
                    ->whereBetween('longitude', [$filters['min_lng'], $filters['max_lng']]);
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
