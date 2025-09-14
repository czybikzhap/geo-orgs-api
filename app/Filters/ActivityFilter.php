<?php

namespace App\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Activity;

class ActivityFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        if (empty($value)) {
            return;
        }

        $activityIds = $this->getActivityWithDescendantsIds($value);

        $query->whereHas('activities', function ($q) use ($activityIds) {
            $q->whereIn('activities.id', $activityIds);
        });
    }

    private function getActivityWithDescendantsIds($activityId): array
    {
        $activity = Activity::with('descendants')->find($activityId);

        if (!$activity) {
            return [$activityId];
        }

        $allIds = [$activity->id];
        $this->collectDescendantsIds($activity, $allIds, 0);

        return $allIds;
    }

    private function collectDescendantsIds($activity, array &$ids, int $level)
    {
        if ($level >= 2) {
            return;
        }

        foreach ($activity->descendants as $descendant) {
            $ids[] = $descendant->id;
            $this->collectDescendantsIds($descendant, $ids, $level + 1);
        }
    }
}
