<?php

namespace App\Http\Livewire\Tasks;

use App\Enums\Tasks\Frequency;
use App\Services\Tasks\TaskCreate;
use LivewireUI\Modal\ModalComponent;

class CreateModal extends ModalComponent
{
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

        if ($this->frequency['value'] === 'DAILY') {
            $rules['frequency.days'] = 'required|array|min:1';
        }

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

    public static function modalMaxWidth(): string
    {
        return '3xl';
    }

    public function setFrequency($value)
    {
        $this->frequency['value'] = $value;
    }

    public function createTask()
    {
        $this->validate();

        dd('àsa');
    }


    public function getTaskCreateResolver(): TaskCreate
    {
        return resolve(TaskCreate::class);
    }
}
