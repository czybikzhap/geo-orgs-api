<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShowOrganizationRequest;
use App\Http\Requests\GetByBuildingRequest;
use App\Http\Requests\GetByActivityRequest;
use App\Http\Requests\NearOrganizationsRequest;
use App\Http\Requests\SearchByNameRequest;
use App\Services\OrganizationService;
use Illuminate\Http\JsonResponse;

class OrganizationController extends Controller
{
    public function __construct(private OrganizationService $service) {}

    public function show(ShowOrganizationRequest $request): JsonResponse
    {
        $id = $request->route('id');
        $org = $this->service->getById($id);
        return response()->json($org);
    }

    public function getByBuilding(GetByBuildingRequest $request): JsonResponse
    {
        $buildingId = $request->route('buildingId');
        $orgs = $this->service->getByBuilding($buildingId);
        return response()->json($orgs);
    }

    public function near(NearOrganizationsRequest $request): JsonResponse
    {
        $result = $this->service->near($request->lat, $request->lng, $request->radius);
        return response()->json($result);
    }

    public function getByActivity(GetByActivityRequest $request): JsonResponse
    {
        $activityId = $request->route('activityId');
        $orgs = $this->service->getByActivity($activityId);
        return response()->json($orgs);
    }

    public function searchByActivityTree(GetByActivityRequest $request): JsonResponse
    {
        $activityId = $request->route('activityId');
        $orgs = $this->service->searchByActivityTree($activityId);
        return response()->json($orgs);
    }

    public function searchByName(SearchByNameRequest $request): JsonResponse
    {
        $searchTerm = $request->query('q');
        $orgs = $this->service->searchByName($searchTerm);
        return response()->json($orgs);
    }
}
