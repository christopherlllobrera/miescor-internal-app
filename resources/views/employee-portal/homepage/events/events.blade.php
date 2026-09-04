<section class="relative bg-slate-50">
    <div id="events" class="w-full py-24 px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-3xl text-center mb-12">
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-[#111827] mb-4">
                Events
            </h2>
            <p class="text-[#6b7280] text-base font-normal sm:text-lg">
                Stay updated with the latest events, activities, and happenings at MIESCOR.
            </p>
        </div>

        <!-- Events List -->
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($events->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    @foreach($events as $event)
                        <div class="bg-white rounded-xl shadow-md p-5 hover:shadow-lg transition">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full {{ $event->dot_color }}"></span>
                                    <p class="text-sm font-medium text-[#111827]">{{ $event->formatted_date }}</p>
                                </div>
                            </div>
                            <h4 class="text-lg font-semibold text-[#111827] mb-1">{{ $event->title }}</h4>
                            <p class="text-[#6B7280] text-sm">{{ $event->description }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-[#111827] mb-2">No Upcoming Events</h3>
                    <p class="text-gray-500 text-sm">Check back later for new events and activities.</p>
                </div>
            @endif
        </div>
    </div>
</section>
