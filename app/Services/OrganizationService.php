<?php

namespace App\Services;

use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Models\Organization;
use App\Filters\SearchFilter; // только поиск по name
use App\Filters\RadiusFilter;
use App\Filters\BoundingBoxFilter;
use Illuminate\Http\Request;

class OrganizationService
{
    public function __construct(private Request $request) {}

    public function getOrganizations(array $filters)
    {
        return QueryBuilder::for(Organization::class)
            ->with(['building', 'phones', 'activities'])
            ->allowedFilters([
                AllowedFilter::custom('search', new SearchFilter()),
                AllowedFilter::exact('building_id'),
                AllowedFilter::exact('activities.id'),
                AllowedFilter::callback('activity_id', function ($query, $value) {
                    $query->whereHas('activities', fn($q) => $q->where('activities.id', $value));
                }),
                AllowedFilter::custom('radius_filter', new RadiusFilter()),
                AllowedFilter::custom('bbox', new BoundingBoxFilter()),
            ])
            ->defaultSort('name')
            ->paginate(10);
    }

    function getById(int $id): Organization
    {
        return QueryBuilder::for(Organization::class)
            ->allowedIncludes(['building', 'phones', 'activities'])
            ->findOrFail($id);
    }
}
