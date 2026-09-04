<section class="relative bg-gray-50">
    <div id="events" class="w-full py-24 px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-3xl">
            <x-portal.section-heading
                title="Events"
                subtitle="Stay updated with the latest events, activities, and happenings at MIESCOR."
            />
        </div>

        {{-- Events List --}}
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($events->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    @foreach($events as $event)
                        <x-portal.card :hover="true">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full {{ $event->dot_color }}"></span>
                                    <p class="text-sm font-medium text-gray-950">{{ $event->formatted_date }}</p>
                                </div>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-950 mb-1">{{ $event->title }}</h4>
                            <p class="text-gray-500 text-sm">{{ $event->description }}</p>
                        </x-portal.card>
                    @endforeach
                </div>
            @else
                <x-portal.empty-state
                    heading="No Upcoming Events"
                    description="Check back later for new events and activities."
                >
                    <x-slot:icon>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                        </svg>
                    </x-slot:icon>
                </x-portal.empty-state>
            @endif
        </div>
    </div>
</section>
