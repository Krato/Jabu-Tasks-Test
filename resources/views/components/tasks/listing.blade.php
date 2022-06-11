<nav class="h-full overflow-y-auto" aria-label="Directory" style="max-height: 700px">

    @foreach($list as $type => $items)
        <div class="relative">
            <div class="sticky top-0 z-10 px-6 py-1 text-sm font-medium text-gray-500 border-t border-b border-gray-200 bg-gray-50">
                <h3>{{ $type }}</h3>
            </div>
            <ul role="list" class="relative z-0 divide-y divide-gray-200">
                @foreach($items as $item)
                    <li class="bg-white">
                        <div class="relative flex items-center px-6 py-5 space-x-3 hover:bg-gray-50 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-500">
                            <div class="flex-shrink-0">
                                <img class="w-10 h-10 rounded-full" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
                            </div>
                            <div class="flex-1 min-w-0">
                                <a href="#" class="focus:outline-none">
                                    <span class="absolute inset-0" aria-hidden="true"></span>
                                    <p class="text-sm font-medium text-gray-900">{{ $item->title }}</p>
                                    <p class="text-sm text-gray-500 truncate">DATE: {{ $item->start->format('Y-m-d') }}</p>
                                    <p class="text-sm text-gray-500 truncate">MAXTIMES: {{ $item->times }}</p>
                                    <p class="text-sm text-gray-500 truncate">TIMES: {{ $item->timespent }}</p>
                                </a>
                            </div>
                        </div>
                  </li>
                @endforeach
            </ul>
        </div>
    @endforeach
</nav>
