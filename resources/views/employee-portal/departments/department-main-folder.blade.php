<div id="departments" class="bg-zinc-50">
    <div class="mx-auto w-full max-w-screen-7xl px-4 py-8 sm:py-12 lg:py-16">
        <div class="text-center mb-8 sm:mb-12">
            <h1 class="text-3xl sm:text-3xl md:text-4xl lg:text-5xl font-bold tracking-tight text-[#111827] mb-4 sm:mb-6">
                Departments
            </h1>
            <p class="mt-4 text-[#6b7280] text-base sm:text-lg max-w-2xl mx-auto">
                Explore our departments and discover resources, FAQs, and workflows.
            </p>
        </div>

        @if($departments->count() > 0)
            <ul class="grid grid-cols-2 xs:grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-7 gap-4 sm:gap-6">
                @foreach($departments as $department)
                    <li class="mb-4 flex flex-col items-center gap-y-2">
                        <a href="{{ route('department.show', $department->cms_department_slug) }}"
                           class="w-24 h-24 bg-orange-600 hover:bg-orange-500 rounded-lg flex items-center justify-center sm:w-28 sm:h-28 md:w-32 md:h-32 lg:w-36 lg:h-36 transition-colors">

                           @if($department->cms_icon)
                                @php
                                    $icon = str_replace('heroicon-o-', 'heroicon-s-', $department->cms_icon);
                                @endphp
                                <x-dynamic-component
                                    :component="$icon"
                                    class="w-8 h-8 sm:w-12 sm:h-12 text-white"
                                />
                            @else
                                {{-- Default folder icon --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 sm:w-12 sm:h-12 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 0 0-1.883 2.542l.857 6a2.25 2.25 0 0 0 2.227 1.932H19.05a2.25 2.25 0 0 0 2.227-1.932l.857-6a2.25 2.25 0 0 0-1.883-2.542m-16.5 0V6A2.25 2.25 0 0 1 6 3.75h3.879a1.5 1.5 0 0 1 1.06.44l2.122 2.12a1.5 1.5 0 0 0 1.06.44H18A2.25 2.25 0 0 1 20.25 9v.776" />
                                </svg>
                            @endif
                        </a>
                        <span class="text-[#374151] text-center h-20 flex items-center text-sm sm:text-base">
                            {{ $department->display_name }}
                        </span>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="text-center py-12">
                <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 0 0-1.883 2.542l.857 6a2.25 2.25 0 0 0 2.227 1.932H19.05a2.25 2.25 0 0 0 2.227-1.932l.857-6a2.25 2.25 0 0 0-1.883-2.542m-16.5 0V6A2.25 2.25 0 0 1 6 3.75h3.879a1.5 1.5 0 0 1 1.06.44l2.122 2.12a1.5 1.5 0 0 0 1.06.44H18A2.25 2.25 0 0 1 20.25 9v.776" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-[#111827] mb-2">No Departments Available</h3>
                <p class="text-[#6b7280] text-sm">Departments will appear here once they are added.</p>
            </div>
        @endif
    </div>
</div>
