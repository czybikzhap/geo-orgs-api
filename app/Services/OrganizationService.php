<?php

namespace App\Services;

use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Models\Organization;
use App\Filters\RadiusFilter;
use App\Filters\BoundingBoxFilter;
use App\Filters\ActivityFilter;
use App\Filters\SearchFilter;

class OrganizationService
{
    public function getOrganizations(array $filters)
    {
        return QueryBuilder::for(Organization::class)
            ->with(['building', 'phones', 'activities'])
            ->allowedFilters([
                AllowedFilter::custom('search', new SearchFilter()),
                AllowedFilter::exact('building_id'),
                AllowedFilter::custom('activity_id', new ActivityFilter()),
                AllowedFilter::custom('radius', new RadiusFilter()),
                AllowedFilter::custom('bbox', new BoundingBoxFilter()),
            ])
            ->defaultSort('name')
            ->paginate(10);
    }

    public function getById(int $id): Organization
    {
        return Organization::with(['building', 'phones', 'activities'])
            ->findOrFail($id);
    }
}
