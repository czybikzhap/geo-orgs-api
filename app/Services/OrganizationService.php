<?php

namespace App\Services;

use App\Filters\OrganizationFilter;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Builder;

class OrganizationService
{
    public function __construct(private OrganizationFilter $filter) {}

    public function getOrganizations(array $filters): Builder
    {
        $query = Organization::query()->with(['building', 'phones', 'activities']);
        return $this->filter->apply($query, $filters);
    }

    public function getById(int $id): Organization
    {
        return Organization::with(['building', 'phones', 'activities'])->findOrFail($id);
    }
}
