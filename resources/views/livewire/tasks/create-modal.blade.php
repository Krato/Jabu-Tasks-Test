<div>
    <div class="px-4 py-5 bg-white border-b border-gray-200 rounded-lg sm:px-6">
        <h3 class="text-lg font-medium leading-6 text-gray-900">Create task</h3>
    </div>
    <div class="grid grid-cols-1 gap-4 px-4 pt-5 pb-8 sm:px-6 md:grid-cols-2">

        <x-uiinput wire:model="task.title" label="Task title" placeholder="Task title" />

        <x-uiinput wire:model="task.times" type="number" label="Núm. of iterations (0 means infinite)" placeholder="Numbers of iteration" />

        <x-datetime-picker
            label="Task start date"
            placeholder="Task start date"
            parse-format="YYYY-MM-DD"
            display-format="YYYY-MM-DD"
            wire:model.defer="task.start"
            without-time="true"
        />

        <x-datetime-picker
            label="Task finish date"
            placeholder="Task finish date"
            parse-format="YYYY-MM-DD"
            display-format="YYYY-MM-DD"
            wire:model.defer="task.finish"
            without-time="true"
        />

        <hr class="col-span-2">

        <div class="col-span-2">
            <div class="relative z-0 inline-flex rounded-md shadow-sm ">
                <button type="button" class="relative inline-flex items-center px-4 py-2 text-sm font-medium border border-gray-300 rounded-l-md focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500
                    {{ ($frequency['value'] === 'DAILY') ? 'bg-blue-700 text-white' : 'bg-white  text-gray-700' }} }}"
                    wire:click="setFrequency('DAILY')">
                    Every day
                </button>
                <button type="button" class="relative inline-flex items-center px-4 py-2 text-sm font-medium border border-gray-300 rounded-l-md focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500
                    {{ ($frequency['value'] === 'WEEKLY') ? 'bg-blue-700 text-white' : 'bg-white  text-gray-700' }} }}"
                    wire:click="setFrequency('WEEKLY')">
                    Every days of Week
                </button>
                <button type="button" class="relative inline-flex items-center px-4 py-2 text-sm font-medium border border-gray-300 rounded-l-md focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500
                    {{ ($frequency['value'] === 'MONTHLY') ? 'bg-blue-700 text-white' : 'bg-white  text-gray-700' }} }}"
                    wire:click="setFrequency('MONTHLY')">
                    Every day/s of month/s
                </button>
                <button type="button" class="relative inline-flex items-center px-4 py-2 text-sm font-medium border border-gray-300 rounded-l-md focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500
                        {{ ($frequency['value'] === 'YEARLY') ? 'bg-blue-700 text-white' : 'bg-white  text-gray-700' }} }}"
                    wire:click="setFrequency('YEARLY')">
                    Every day of year
                </button>
            </div>
            @if($errors->has('frequency.value'))
            <p class="w-full mt-2 text-sm text-negative-600">
                {{ $errors->first('frequency.value') }}
            </p>
        @endif
        </div>


        @if($frequency['value'] == 'WEEKLY')
            <div class="" wire:key="weekly">
                <x-select
                    label="Day/s of week"
                    placeholder="Day/s of week"
                    :options="['MO', 'TU', 'WE', 'TH', 'FR', 'SA', 'SU']"
                    wire:model.defer="frequency.weekdays"
                    multiselect
                />
            </div>
        @endif

        @if($frequency['value'] == 'MONTHLY')
            <div class="" wire:key="days_monthly">
                <x-select
                    label="Day/s of month"
                    placeholder="Day/s of month"
                    :options="['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31']"
                    wire:model.defer="frequency.days"
                    multiselect
                />
            </div>
            <div class="" wire:key="months_monthly">
                <x-select
                    label="Month/s of year"
                    placeholder="Day/s of year"
                    :options="[
                        1 => 'January',
                        2 => 'February',
                        3 => 'March',
                        4 => 'April',
                        5 => 'May',
                        6 => 'June',
                        7 => 'July',
                        8 => 'August',
                        9 => 'September',
                        10 => 'October',
                        11 => 'November',
                        12 => 'December',
                    ]"
                    wire:model.defer="frequency.months"
                    multiselect
                />
            </div>
        @endif

        @if($frequency['value'] == 'YEARLY')
        <div class="" wire:key="yearly_months_days">
            <x-select
                label="Day/s of month"
                placeholder="Day/s of month"
                :options="['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31']"
                wire:model.defer="frequency.days"
                multiselect
            />
        </div>
        <div class="" wire:key="yearly_months">
            <x-select
                label="Month/s of year"
                placeholder="Day/s of year"
                :options="$months"
                option-label="name"
                option-value="id"
                wire:model.defer="frequency.months"
            />
        </div>
        @endif
    </div>
    <div class="flex justify-end flex-shrink-0 px-4 py-3 bg-gray-200 rounded-lg sm:px-6">
        <x-button wire:click="createTask" wire:target="createTask" wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="createTask">Create Task</span>
            <span wire:loading.flex wire:target="createTask" class="flex items-center">
                <svg class="w-5 h-5 mr-3 -ml-1 text-white animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Processing...</span>
            </span>
        </x-button>
        {{-- <button type="button" class="inline-flex items-center w-auto px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            wire:click="createTask" wire:target="createTask" wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="createTask">Add Task</span>
            <span wire:loading wire:target="createTask" class="flex flex-wrap items-center">
                <svg class="w-5 h-5 mr-3 -ml-1 text-white animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Processing...
            </span>
        </button> --}}
    </div>
</div>
