<?php

namespace App\Http\Livewire\Tasks;

use App\Enums\Tasks\Frequency;
use App\Events\Tasks\TaskCreated;
use App\Http\Livewire\Tasks\Index;
use App\Services\Tasks\TaskCreate;
use App\Services\Tasks\TaskFrequency;
use DateTime;
use LivewireUI\Modal\ModalComponent;
use WireUi\Traits\Actions;

class CreateModal extends ModalComponent
{
    use Actions;

    public $task = [
        'title' => '',
        'start' => '',
        'finish' => '',
        'times' => 0,
    ];

    public $frequency = [
        'value' => '',
        'days'  => [],
        'weekdays'  => [],
        'months'  => [],
    ];

    public $months = [
        ['name' => 'January',  'id' => 1],
        ['name' => 'February', 'id' => 2],
        ['name' => 'March',    'id' => 3],
        ['name' => 'April',    'id' => 4],
        ['name' => 'May',      'id' => 5],
        ['name' => 'June',     'id' => 6],
        ['name' => 'July',     'id' => 7],
        ['name' => 'August',   'id' => 8],
        ['name' => 'September','id' => 9],
        ['name' => 'October',  'id' => 10],
        ['name' => 'November', 'id' => 11],
        ['name' => 'December', 'id' => 12],
    ];

    protected $validationAttributes = [
        'task.title' => 'title',
        'task.start' => 'start',
        'task.finish' => 'finish',
        'task.times' => 'times',
        'frequency.value' => 'frequency',
        'frequency.days' => 'days',
        'frequency.weekdays' => 'weekdays',
        'frequency.months' => 'months',
    ];

    protected function rules()
    {
        $rules = [
            'task.title' => 'required|string',
            'task.start' => 'required|date',
            'task.finish' => 'required|date|after:task.start',
            'task.times' => 'required|integer|min:0',
            'frequency.value' => 'required|string|in:'.implode(',', Frequency::names()),
        ];

        if ($this->frequency['value'] === 'WEEKLY') {
            $rules['frequency.weekdays'] = 'required|array|min:1';
        }

        if ($this->frequency['value'] === 'MONTHLY') {
            $rules['frequency.days'] = 'required|array|min:1';
            $rules['frequency.months'] = 'required|array|min:1';
        }

        if ($this->frequency['value'] === 'YEARLY') {
            $rules['frequency.days'] = 'required|array|min:1';
            $rules['frequency.months'] = 'required|in:1,2,3,4,5,6,7,8,9,10,11,12';
        }

        return $rules;
    }

    public function render()
    {
        return view('livewire.tasks.create-modal');
    }


    public function setFrequency($value)
    {
        $this->frequency['value'] = $value;
    }

    public function createTask()
    {
        $this->validate();

        $frequency = Frequency::getByName($this->frequency['value']);

        $taskFrequency = new TaskFrequency(
            frequency: $frequency,
            weekDays:$this->frequency['weekdays'],
            months: is_array($this->frequency['months']) ? $this->frequency['months'] : [$this->frequency['months']],
            monthDays: is_array($this->frequency['days']) ? $this->frequency['days'] : [$this->frequency['days']],
        );

        $task = (new TaskCreate)->create(
            user: auth()->user(),
            startDate: new DateTime($this->task['start']),
            endDate: new DateTime($this->task['finish']),
            title: $this->task['title'],
            taskFrequency: $taskFrequency,
            times: $this->task['times'],
        );

        event(new TaskCreated($task));

        $this->notification()->success(
            'Task created',
            'The task hass been created successfully'
        );

        $this->closeModalWithEvents([
            Index::getName() => 'getTasks',
        ]);
    }

    public static function modalMaxWidth(): string
    {
        return '3xl';
    }

    public static function closeModalOnClickAway(): bool
    {
        return false;
    }
}
