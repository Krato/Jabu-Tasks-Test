<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Tasks') }}
        </h2>
    </x-slot>

    <div class="py-12">

        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-end w-full">
                <x-button onclick="Livewire.emit('openModal', 'tasks.create-modal')">
                    Add Task
                </x-button>
            </div>
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <livewire:tasks.index />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
