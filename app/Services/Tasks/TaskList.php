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
     *
     * @return void
     */
    public function getTasks(User $user, ?ListBy $listBy = null)
    {
        $filterBy = $listBy != null ? [$listBy] : ListBy::cases();

        // Get pending items tasks
        $items = $this->getTasksItems($user, $filterBy);

        // Format founded tasks
        $formatted = $items->map(function ($item) {
            return (object) $item->formatted();
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
    private function getTasksItems(User $user, array $filterBy): Collection
    {
        return TaskItems::whereHas('task', function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->whereStatus(Status::PENDING);
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
                    return $item->start->format('Y-m-d') == $filter->getDates()['start'];
                }

                return $item->start >= $filter->getDates()['start'] && $item->start <= $filter->getDates()['end'];
            });
        }

        return $list;
    }
}
