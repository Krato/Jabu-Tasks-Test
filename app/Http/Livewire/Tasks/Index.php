<?php

namespace App\Http\Livewire\Tasks;

use App\Enums\Tasks\Status;
use App\Models\TaskItems;
use App\Services\Tasks\TaskAction;
use App\Services\Tasks\TaskList;
use Livewire\Component;
use WireUi\Traits\Actions;

class Index extends Component
{
    use Actions;

    public array $tasks;

    protected $listeners = [
        'getTasks'
    ];

    public function mount()
    {
        $this->getTasks();
    }

    public function render()
    {
        return view('livewire.tasks.index');
    }

    public function getTaskListResolver(): TaskList
    {
        return resolve(TaskList::class);
    }

    public function getTasks()
    {
        $this->tasks = $this->getTaskListResolver()->getTasks(auth()->user(), null, Status::PENDING);
    }


    /**
     * Set ietm as completed
     *
     * @param int $id
     * @param \App\Services\Tasks\TaskAction $taskAction
     *
     * @return void
     */
    public function setAsCompleted($id, TaskAction $taskAction)
    {
        $item = TaskItems::with('task')->find($id);

        if ($item->status === Status::PENDING) {
            $taskAction->completeItem($item);

            $item->refresh();

            // Check if parent task is completed
            if ($item->task->status === Status::COMPLETED) {
                $this->notification()->success(
                    'Task completed',
                    'The task has been completed',
                );
            } else {
                $this->notification()->success(
                    'Item Task completed',
                    'The item task has been marked as completed'
                );
            }

            $this->getTasks();
        }
    }
}
