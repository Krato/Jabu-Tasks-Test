<?php

namespace App\Console\Commands\Tasks;

use App\Enums\Tasks\Status;
use App\Models\Task;
use App\Services\Tasks\TaskAction;
use Illuminate\Console\Command;

class ProcessTasksCounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:process-tasks-counts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process tasks counts';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(TaskAction $taskAction)
    {
        $tasks = Task::whereStatus(Status::PENDING)
            ->withWhereHas('items', function ($query) {
                $query->whereStatus(Status::PENDING)
                    ->where('start', '<=', now()->yesterday());
            })->get();

        foreach ($tasks as $task) {
            foreach ($task->items as $item) {
                // Set subitem as complete
                $taskAction->completeItem($item);

                // Add count to task
                $taskAction->addCount($task);
            }
        }

        $this->info('Tasks counts processed');

        return 0;
    }
}
