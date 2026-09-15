<x-filament-widgets::widget>
    @if(count($announcements) > 0)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-2">
            @foreach($announcements as $announcement)
                @php
                    $isReminder = $announcement->type === 'Reminder';
                    $bgClass = $isReminder ? 'bg-orange-700' : 'bg-white ring-1 ring-gray-950/10 dark:bg-gray-900 dark:ring-white/20';
                    $textClass = $isReminder ? 'text-white' : 'text-gray-950 dark:text-white';
                    $timerBg = $isReminder ? 'bg-white' : 'bg-gray-100 dark:bg-gray-800';
                    $timerText = $isReminder ? 'text-orange-700' : 'text-gray-950 dark:text-white';
                    $labelClass = $isReminder ? 'text-slate-50' : 'text-gray-500 dark:text-gray-400';
                    $closeIconClass = $isReminder ? 'fill-slate-50' : 'fill-gray-500 dark:fill-gray-400';
                @endphp
                
                <div x-data="{
                        show: true,
                        endDate: new Date('{{ \Carbon\Carbon::parse($announcement->ends_date)->endOfDay()->toIso8601String() }}').getTime(),
                        days: '00',
                        hours: '00',
                        minutes: '00',
                        updateTimer() {
                            let distance = this.endDate - new Date().getTime();
                            if (distance < 0) {
                                this.days = '00'; this.hours = '00'; this.minutes = '00';
                                return;
                            }
                            this.days = String(Math.floor(distance / (1000 * 60 * 60 * 24))).padStart(2, '0');
                            this.hours = String(Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).padStart(2, '0');
                            this.minutes = String(Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
                        }
                    }" 
                    x-init="updateTimer(); setInterval(() => updateTimer(), 10000);"
                    x-show="show"
                    x-transition.opacity.duration.300ms
                    role="region" 
                    aria-label="Announcement"
                    class="{{ $bgClass }} px-4 py-3 relative flex items-center justify-center text-center md:px-6 rounded-xl shadow-sm">

                   <div class="w-full flex items-center justify-center sm:justify-between flex-wrap gap-y-4 gap-x-6 pr-6">
                      <p class="text-sm font-medium {{ $textClass }} text-center sm:text-left">
                          {{ $announcement->announcement }}
                      </p>
                      
                      <div class="flex items-center gap-3">
                         <div class="flex items-center gap-1">
                            <span class="text-sm leading-tight font-semibold {{ $timerBg }} {{ $timerText }} px-2.5 py-1.5 rounded-md mx-1" x-text="days">00</span>
                            <span class="text-xs {{ $labelClass }}">DAYS</span>
                         </div>
                         <div class="flex items-center gap-1">
                            <span class="text-sm leading-tight font-semibold {{ $timerBg }} {{ $timerText }} px-2.5 py-1.5 rounded-md mx-1" x-text="hours">00</span>
                            <span class="text-xs {{ $labelClass }}">HRS</span>
                         </div>
                         <div class="flex items-center gap-1">
                            <span class="text-sm leading-tight font-semibold {{ $timerBg }} {{ $timerText }} px-2.5 py-1.5 rounded-md mx-1" x-text="minutes">00</span>
                            <span class="text-xs {{ $labelClass }}">MIN</span>
                         </div>
                      </div>
                   </div>

                   <button type="button" @click="show = false" aria-label="Dismiss notification banner"
                      class="absolute right-4 focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-400 rounded transition hover:opacity-75">
                      <svg xmlns="http://www.w3.org/2000/svg" class="size-3 cursor-pointer {{ $closeIconClass }}" aria-hidden="true"
                         viewBox="0 0 329.269 329">
                         <path d="M194.8 164.77 323.013 36.555c8.343-8.34 8.343-21.825 0-30.164-8.34-8.34-21.825-8.34-30.164 0L164.633 134.605 36.422 6.391c-8.344-8.34-21.824-8.34-30.164 0-8.344 8.34-8.344 21.824 0 30.164l128.21 128.215L6.259 292.984c-8.344 8.34-8.344 21.825 0 30.164a21.27 21.27 0 0 0 15.082 6.25c5.46 0 10.922-2.09 15.082-6.25l128.21-128.214 128.216 128.214a21.27 21.27 0 0 0 15.082 6.25c5.46 0 10.922-2.09 15.082-6.25 8.343-8.34 8.343-21.824 0-30.164zm0 0" />
                      </svg>
                   </button>
                </div>
            @endforeach
        </div>
    @else
        <x-filament::section>
            <div class="flex items-center gap-3">
                <x-filament::icon
                    icon="heroicon-o-information-circle"
                    class="h-5 w-5 text-gray-400"
                />
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    No active payroll announcement for this period.
                </p>
            </div>
        </x-filament::section>
    @endif
</x-filament-widgets::widget>
