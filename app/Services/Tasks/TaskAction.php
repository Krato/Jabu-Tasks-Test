<?php

namespace App\Services\Tasks;

use App\Enums\Tasks\Status;
use App\Models\Task;
use App\Models\TaskItems;

class TaskAction
{

    /**
     * Add timespent count to task
     *
     * @param \App\Models\Task $task
     *
     * @return int
     */
    public function addCount(Task $task): int
    {
        if ($task->times === $task->timespent) {
            return $task->times;
        }

        $task->timespent++;
        $task->save();

        // Refresh task
        $task->refresh();

        if ($task->timespent === $task->times) {
            $this->complete($task);
        }

        return $task->timespent;
    }


    /**
     * Set task as complete
     *
     * @param \App\Models\Task $task
     *
     * @return bool
     */
    public function complete(Task $task): bool
    {
        $task->status = Status::COMPLETED;
        $task->completed_at = now();

        return $task->save();
    }

    /**
     * Set task as pending
     *
     * @param \App\Models\Task $task
     *
     * @return bool
     */
    public function pending(Task $task): bool
    {
        $task->status = Status::PENDING;
        $task->completed_at = null;

        return $task->save();
    }

    /**
     * Set task item as complete
     *
     * @param \App\Models\TaskItems $item
     *
     * @return bool
     */
    public function completeItem(TaskItems $item): bool
    {
        $item->status = Status::COMPLETED;
        $item->completed_at = now();

        return $item->save();
    }

    /**
     * Set task item as pending
     *
     * @param \App\Models\TaskItems $item
     *
     * @return bool
     */
    public function pendingItem(TaskItems $item): bool
    {
        $item->status = Status::PENDING;
        $item->completed_at = null;

        return $item->save();
    }
}
