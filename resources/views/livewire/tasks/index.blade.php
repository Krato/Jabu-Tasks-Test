<div class="w-full">

    <nav class="h-full overflow-y-auto" aria-label="Directory" style="max-height: 500px">

        @foreach($tasks as $type => $items)
            <div class="relative">
                <div class="sticky top-0 z-10 px-6 py-4 text-sm font-medium text-gray-500 uppercase border-t border-b border-gray-200 bg-gray-50">
                    <h3>{{ fixName($type) }}</h3>
                </div>
                <ul role="list" class="relative z-0 divide-y divide-gray-200">
                    @foreach($items as $item)
                        <li class="bg-white" :wire:key="'task-item-'.$item['id']">
                            <div class="relative flex items-center px-6 py-5 space-x-3 hover:bg-gray-50 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-500">
                                <div class="flex-shrink-0">
                                    @if($item['status'] == 'COMPLETED')
                                        <div class="flex items-center justify-center w-10 h-10 bg-green-200 rounded-full">
                                            <svg class="text-green-500 w-7 h-7" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                <path class="fill-current" d="M12 0a12 12 0 1 0 12 12A12 12 0 0 0 12 0Zm-.48 17L6 12.79l1.83-2.37L11.14 13l4.51-5.08 2.24 2Z"/>
                                            </svg>
                                        </div>
                                    @else
                                        <div class="flex items-center justify-center w-10 h-10 bg-gray-100 rounded-full cursor-pointer">
                                            <svg class="text-gray-500 w-7 h-7" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                <path class="fill-current" d="M12-.2a12 12 0 1 0 12 12 12 12 0 0 0-12-12ZM18 13h-7V5h2v6h5v2Z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <a href="#" class="flex justify-between focus:outline-none" wire:click="setAsCompleted({{ $item['id'] }})">
                                        <span class="absolute inset-0" aria-hidden="true"></span>
                                        <div class="">
                                            <p class="text-sm font-medium text-gray-900">{{ $item['title'] }}</p>
                                            <p class="text-sm text-gray-800 truncate">{{ $item['start'] }}</p>
                                        </div>

                                        @if($item['times'] > 0)
                                            <div class="">
                                                <p class="text-sm text-gray-500 truncate">MAXTIMES: {{ $item['times'] }}</p>
                                                <p class="text-sm text-gray-500 truncate">HAPPENED: {{ $item['timespent'] }}</p>
                                            </div>
                                        @endif
                                    </a>
                                </div>
                            </div>


                    </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </nav>
</div>
