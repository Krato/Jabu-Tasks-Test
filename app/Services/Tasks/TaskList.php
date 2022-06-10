<?php

namespace App\Services\Tasks;

use App\Enums\Tasks\ListBy;
use App\Enums\Tasks\Status;
use App\Models\Task;
use App\Models\TaskItems;
use App\Models\User;

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
        $items = TaskItems::whereHas('task', function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->whereStatus(Status::PENDING);
        })->where(function ($q) use ($filterBy) {
            foreach ($filterBy as $filter) {
                $q->orWhereBetween('start', $filter->getDates());
            }
        })->orderBy('start', 'asc');

        return $items->get()->map(function ($item) {
            return (object) [
                'id' => $item->task->id,
                'user_id' => $item->task->user_id,
                'title' => $item->task->title,
                'start' => $item->start,
                'status' => $item->task->status,
                'times' => $item->task->times,
                'timespent' => $item->task->timespent,
            ];
        });
    }
}
