<?php

namespace App\Services\Tasks;

use App\Enums\Tasks\Status;
use App\Models\Task;

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

        return $task->save();
    }
}
