<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\Building;
use App\Models\Organization;
use Illuminate\Support\Facades\DB;

class OrganizationService
{
    public function getById(int $id): Organization
    {
        return Organization::with(['phones', 'activities', 'building'])->findOrFail($id);
    }

    public function getByBuilding(int $buildingId)
    {
        return Organization::where('building_id', $buildingId)->get();
    }

    public function near(float $lat, float $lng, float $radius = 5)
    {
        $subquery = Building::selectRaw("
            id, address, latitude, longitude,
            ( 6371 * acos(cos(radians(?)) * cos(radians(latitude)) *
            cos(radians(longitude) - radians(?)) +
            sin(radians(?)) * sin(radians(latitude))) ) AS distance
            ", [$lat, $lng, $lat]);

        $buildings = DB::table(DB::raw("({$subquery->toSql()}) AS sub"))
            ->mergeBindings($subquery->getQuery())
            ->where('distance', '<=', $radius)
            ->get();

        $organizations = Organization::whereIn('building_id', $buildings->pluck('id'))->get();

        return [
            'buildings' => $buildings,
            'organizations' => $organizations
        ];
    }

    public function getByActivity(int $activityId)
    {
        $level2 = Activity::where('parent_id', $activityId)->pluck('id');
        $level3 = Activity::whereIn('parent_id', $level2)->pluck('id');

        $allActivityIds = collect([$activityId])
            ->merge($level2)
            ->merge($level3);

        return Organization::whereHas('activities', fn($q) => $q->whereIn('activities.id', $allActivityIds))
            ->get();
    }

    public function searchByActivityTree(int $activityId)
    {
        $ids = $this->getActivityIdsRecursive($activityId);

        return Organization::whereHas('activities', fn($q) => $q->whereIn('activities.id', $ids))->get();
    }

    private function getActivityIdsRecursive(int $id): array
    {
        $ids = [$id];
        $children = Activity::where('parent_id', $ids)->pluck('id');

        foreach ($children as $childId) {
            $ids = array_merge($ids, $this->getActivityIdsRecursive($childId));
        }

        return $ids;
    }

    public function searchByName(string $searchTerm)
    {
        return Organization::where('name', 'ILIKE', "%{$searchTerm}%")->get();
    }
}
