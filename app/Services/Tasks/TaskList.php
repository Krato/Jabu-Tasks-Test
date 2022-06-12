<?php

namespace App\Services\Tasks;

use App\Enums\Tasks\ListBy;
use App\Enums\Tasks\Status;
use App\Models\Task;
use App\Models\TaskItems;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as ObjectCollection;

class TaskList
{
    /**
     * Get list of tasks for given user and by current filter
     *
     * @param \App\Models\User $user
     * @param \App\Enums\Tasks\ListBy|null $listBy
     * @param \App\Enums\Tasks\Status|null $status
     *
     * @return array
     */
    public function getTasks(User $user, ?ListBy $listBy = null, ?Status $status = null): array
    {
        $filterBy = $listBy != null ? [$listBy] : ListBy::cases();
        $filterStatus =  $status != null ? [$status] : Status::cases();

        // Get pending items tasks
        $items = $this->getTasksItems($user, $filterBy, $filterStatus);

        // Format founded tasks
        $formatted = $items->map(function ($item) {
            return $item->toArray();
        });

        return $this->groupByFilters($formatted, $filterBy);
    }

    /**
     * Get task items for given user and by current filters
     *
     * @param \App\Models\User $user
     * @param array $filterBy
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getTasksItems(User $user, array $filterBy, array $filterStatus): Collection
    {
        return TaskItems::whereHas('task', function ($query) use ($user, $filterStatus) {
            $query->where('user_id', $user->id)
                ->whereIn('status', $filterStatus);
        })->where(function ($q) use ($filterBy) {
            foreach ($filterBy as $filter) {
                $q->orWhereBetween('start', $filter->getDates());
            }
        })
        ->orderBy('start', 'asc')
        ->get();
    }

    /**
     * Group tasks by filters
     *
     * @param \Illuminate\Support\Collection $formatted
     * @param array $filterBy
     *
     * @return array
     */
    private function groupByFilters(ObjectCollection $items, array $filterBy): array
    {
        $list = [];

        foreach ($filterBy as $filter) {
            $list[$filter->getName()] = $items->filter(function ($item) use ($filter) {
                if (in_array($filter->getName(), ['today', 'tomorrow'])) {
                    return $item['start'] == $filter->getDates()['start'];
                }

                return $item['start'] >= $filter->getDates()['start'] && $item['start'] <= $filter->getDates()['end'];
            });
        }

        return collect($list)->filter(function ($item) {
            return $item->count() > 0;
        })->toArray();
    }
}
