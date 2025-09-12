<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Building;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrganizationController extends Controller
{

    public function show(int $id): object
    {
        $org = Organization::with(['phones', 'activities', 'building'])
            ->findOrFail($id);

        return response()->json($org);
    }

    public function getByBuilding(int $buildingId): object
    {
        $orgs = Organization::where('building_id', $buildingId)->get();

        return response()->json($orgs);
    }

    public function near(Request $request): object
    {
        $lat = $request->lat;
        $lng = $request->lng;
        $radius = $request->radius ?? 5;

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

        $orgs = Organization::whereIn('building_id', $buildings->pluck('id'))->get();

        return response()->json([
            'buildings' => $buildings,
            'organizations' => $orgs
        ]);
    }

    public function getByActivity(int $activityId): object
    {
        $level2 = Activity::where('parent_id', $activityId)->pluck('id');
        $level3 = Activity::whereIn('parent_id', $level2)->pluck('id');

        $allActivityIds = collect([$activityId])
            ->merge($level2)
            ->merge($level3);

        $orgs = Organization::whereHas('activities', fn($q) => $q->whereIn('activities.id', $allActivityIds))
            ->get();

        return response()->json($orgs);
    }

    public function searchByActivityTree(int $activityId): object
    {
        $ids = $this->getActivityIdsRecursive($activityId);

        $orgs = Organization::whereHas('activities', function ($q) use ($ids) {
            $q->whereIn('activities.id', $ids);
        })->get();

        return response()->json($orgs);

    }

    private function getActivityIdsRecursive(int $id)
    {
        $ids = [$id];
        $children = Activity::where('parent_id', $ids)->pluck('id');

        foreach ($children as $childId) {
            $ids = array_merge($ids, $this->getActivityIdsRecursive($childId));
        }

        return $ids;
    }

    public function searchByName(Request $request)
    {
        $searchTerm = $request->query('q'); // получаем строку поиска из ?q=...

        if (!$searchTerm) {
            return response()->json([
                'error' => 'Query parameter "q" is required'
            ], 400);
        }

        $orgs = Organization::where('name', 'ILIKE', "%{$searchTerm}%")->get();

        return response()->json($orgs);
    }

}
