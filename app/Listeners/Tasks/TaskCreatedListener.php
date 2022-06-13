<?php

namespace App\Listeners\Tasks;

use App\Enums\Tasks\Status;
use App\Events\Tasks\TaskCreated;
use App\Services\Tasks\TaskAction;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class TaskCreatedListener
{
    protected $taskAction;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(TaskAction $taskAction)
    {
        $this->taskAction = $taskAction;
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\Tasks\TaskCreated  $event
     * @return void
     */
    public function handle(TaskCreated $event)
    {
        $task = $event->task;

        // Check if task has old items
        $task = $task->withWhereHas('items', function ($query) {
            $query->whereStatus(Status::PENDING)
                ->where('start', '<=', now()->yesterday());
        })->first();

        foreach ($task->items as $item) {
            // Set subitem as complete
            $this->taskAction->completeItem($item);

            // Add count to task
            $this->taskAction->addCount($task);
        }
    }
}
