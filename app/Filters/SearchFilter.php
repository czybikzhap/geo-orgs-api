<?php

namespace App\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Activity;
use Illuminate\Support\Str;

class SearchFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        if (empty($value)) {
            return;
        }

        $query->where(function ($q) use ($value) {
            $q->where('organizations.name', 'ilike', "%{$value}%")
                ->orWhereHas('activities', function ($q) use ($value) {
                    $q->where('name', 'ilike', "%{$value}%");
                })

                ->orWhereHas('activities', function ($q) use ($value) {
                    $activityIds = $this->getActivityIdsByName($value);
                    if (!empty($activityIds)) {
                        $q->whereIn('activities.id', $activityIds);
                    } else {
                        $q->whereRaw('1 = 0');
                    }
                });
        });
    }

    private function getActivityIdsByName(string $activityName): array
    {
        $activity = Activity::where('name', 'ilike', "%{$activityName}%")
            ->orWhere('name', 'ilike', Str::lower($activityName))
            ->orWhere('name', 'ilike', Str::ucfirst($activityName))
            ->first();

        if (!$activity) {
            return [];
        }

        return $this->getActivityWithDescendantsIds($activity);
    }

    private function getActivityWithDescendantsIds(Activity $activity): array
    {
        $allIds = [$activity->id];
        $this->collectDescendantsIds($activity, $allIds, 0);

        return $allIds;
    }

    private function collectDescendantsIds(Activity $activity, array &$ids, int $level)
    {
        if ($level >= 2) {
            return;
        }

        foreach ($activity->children as $child) {
            $ids[] = $child->id;
            $this->collectDescendantsIds($child, $ids, $level + 1);
        }
    }
}
