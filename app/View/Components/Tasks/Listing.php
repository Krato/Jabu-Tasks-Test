<?php

namespace App\View\Components\Tasks;

use App\Services\Tasks\TaskList;
use Illuminate\View\Component;

class Listing extends Component
{
    public $list;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(TaskList $taskList)
    {
        $this->list = $taskList->getTasks(auth()->user());
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.tasks.listing');
    }
}
