<?php

namespace App\Http\Livewire\Tasks;

use App\Models\TaskItems;
use App\Services\Tasks\TaskAction;
use App\Services\Tasks\TaskList;
use Livewire\Component;
use WireUi\Traits\Actions;

class Index extends Component
{
    use Actions;

    public array $tasks;

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
        $this->tasks = $this->getTaskListResolver()->getTasks(auth()->user());
    }

    public function setAsCompleted($id, TaskAction $taskAction)
    {
        $item = TaskItems::find($id);

        $taskAction->completeItem($item);

        $this->notification()->success(
            'Item Task completed',
            'The task hasn been marked as completed'
        );

        $this->getTasks();
    }
}
