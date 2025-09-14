<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexOrganizationRequest;
use App\Http\Requests\ShowOrganizationRequest;
use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use App\Services\OrganizationService;

/**
 * @OA\Info(
 *     title="Organizations REST API",
 *     version="1.0.0",
 *     description="API справочника организаций"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="apiKey",
 *     type="apiKey",
 *     in="header",
 *     name="X-API-KEY"
 * )
 *
 * @OA\OpenApi(
 *     security={{"apiKey": {}}}
 * )
 */
class OrganizationController extends Controller
{
    public function __construct(private OrganizationService $service) {}

    /**
     * @OA\Get(
     *     path="/api/organizations",
     *     summary="Список организаций с фильтрацией",
     *     tags={"Organizations"},
     *     security={{"apiKey":{}}},
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Поиск по названию организации",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="building_id",
     *         in="query",
     *         description="Фильтр по ID здания",
     *         required=false,
     *         @OA\Schema(type="integer", example=3)
     *     ),
     *     @OA\Parameter(
     *         name="activity_id",
     *         in="query",
     *         description="Фильтр по ID вида деятельности",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="include_descendants",
     *         in="query",
     *         description="Искать также по дочерним видам деятельности (true/false)",
     *         required=false,
     *         @OA\Schema(type="boolean", example=true)
     *     ),
     *     @OA\Parameter(
     *         name="latitude",
     *         in="query",
     *         description="Центр поиска — широта",
     *         required=false,
     *         @OA\Schema(type="number", format="float", example=55.751244)
     *     ),
     *     @OA\Parameter(
     *         name="longitude",
     *         in="query",
     *         description="Центр поиска — долгота",
     *         required=false,
     *         @OA\Schema(type="number", format="float", example=37.618423)
     *     ),
     *     @OA\Parameter(
     *         name="radius",
     *         in="query",
     *         description="Радиус поиска (в км)",
     *         required=false,
     *         @OA\Schema(type="number", format="float", example=5.0)
     *     ),
     *     @OA\Parameter(
     *         name="min_lat",
     *         in="query",
     *         description="Минимальная широта (для bounding box)",
     *         required=false,
     *         @OA\Schema(type="number", format="float", example=55.7)
     *     ),
     *     @OA\Parameter(
     *         name="max_lat",
     *         in="query",
     *         description="Максимальная широта (для bounding box)",
     *         required=false,
     *         @OA\Schema(type="number", format="float", example=55.8)
     *     ),
     *     @OA\Parameter(
     *         name="min_lng",
     *         in="query",
     *         description="Минимальная долгота (для bounding box)",
     *         required=false,
     *         @OA\Schema(type="number", format="float", example=37.6)
     *     ),
     *     @OA\Parameter(
     *         name="max_lng",
     *         in="query",
     *         description="Максимальная долгота (для bounding box)",
     *         required=false,
     *         @OA\Schema(type="number", format="float", example=37.7)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Список организаций",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Organization")),
     *             @OA\Property(property="links", type="object"),
     *             @OA\Property(property="meta", type="object")
     *         )
     *     )
     * )
     */
    public function index(IndexOrganizationRequest $request)
    {
        $filters = $request->validated();
        $organizations = $this->service->getOrganizations($filters)->paginate(10);
        return OrganizationResource::collection($organizations);
    }

    /**
     * @OA\Get(
     *     path="/api/organizations/{id}",
     *     summary="Получить организацию по ID",
     *     tags={"Organizations"},
     *     security={{"apiKey":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID организации",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Информация об организации",
     *         @OA\JsonContent(ref="#/components/schemas/Organization")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Организация не найдена"
     *     )
     * )
     */
    public function show(ShowOrganizationRequest $request)
    {
        $organization = Organization::findOrFail($request->validated('id'));
        return new OrganizationResource($organization);
    }
}
