<x-portal.layout
    :title="$department->display_name . ' - MIESCOR'"
    :description="'MIESCOR is a leading engineering and construction company in the Philippines.'"
    :keywords="'MIESCOR, Engineering, Construction, ' . $department->display_name"
>
    <x-slot:head>
        {{-- Geist font (falls back to Inter) --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
        <style>
            body { font-family: 'Geist', 'Inter', ui-sans-serif, system-ui, sans-serif; }

            /* ── Accordion – native <details> chevron ──────────────── */
            details summary { list-style: none; }
            details summary::-webkit-details-marker { display: none; }
            details > summary .icon-plus  { display: block; }
            details > summary .icon-minus { display: none;  }
            details[open] > summary .icon-plus  { display: none;  }
            details[open] > summary .icon-minus { display: block; }

            /* ── Smooth accordion body ──────────────────────────────── */
            details .accordion-body {
                overflow: hidden;
                animation: slideDown 0.18s ease-out;
            }
            @keyframes slideDown {
                from { opacity: 0; transform: translateY(-4px); }
                to   { opacity: 1; transform: translateY(0);    }
            }

            /* ── Directory card aspect ratio ────────────────────────── */
            .aspect-portrait { aspect-ratio: 9 / 16; }
        </style>
    </x-slot:head>

    {{-- ═══════════════════ HEADER / NAV ═══════════════════ --}}
    <x-portal.navigation :minimal="true" />

    {{-- ═══════════════════ MAIN ═══════════════════ --}}
    <main class="pt-20 sm:pt-24 max-w-screen-2xl mx-auto px-4 md:px-8 space-y-6 pb-16">

        {{-- ── HERO BANNER ─────────────────────────────────────── --}}
        <section class="mt-6 relative rounded-xl overflow-hidden h-64 sm:h-80 md:h-96 {{ $department->cms_banner ? 'bg-gray-950' : 'bg-linear-to-r from-orange-500 to-orange-400 shadow-sm' }}">
            @if($department->cms_banner)
                <img src="{{ $department->image_url }}"
                     alt="{{ $department->cms_department_name }} Cover"
                     class="absolute inset-0 w-full h-full object-cover">
                {{-- Gradient overlay --}}
                <div class="relative bg-linear-to-r from-orange-500 to-amber-300"></div>
            @endif
            {{-- Content --}}
            <div class="absolute inset-0 flex flex-col justify-end p-6 sm:p-8 md:p-10">
                <h1 class="text-white font-semibold text-2xl sm:text-3xl md:text-4xl leading-tight tracking-tight">
                    {{ $department->display_name }}
                </h1>
                <p class="mt-2 text-white/80 text-sm sm:text-base leading-relaxed max-w-3xl line-clamp-3 sm:line-clamp-none">
                    {{ $department->cms_department_description }}
                </p>
            </div>
        </section>

        {{-- ── DOWNLOADABLE FORMS ───────────────────────────────── --}}
        <x-portal.card padding="p-4 sm:p-6">
            <h2 class="font-semibold text-gray-950 text-lg sm:text-xl md:text-2xl leading-tight tracking-tight mb-4 sm:mb-5">
                Downloadable Forms
            </h2>

            @if ($department->downloadables->count() > 0)
                <ul class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 xl:grid-cols-10 gap-3 sm:gap-4">
                    @foreach ($department->downloadables as $downloadable)
                        @php
                            $iconName    = $downloadable->form_icon
                                         ? trim(str_replace(['"', "'"], '', $downloadable->form_icon))
                                         : 'heroicon-s-document';
                            $hasAttachment = (bool) $downloadable->getRawOriginal('form_attachment');
                            $downloadUrl   = $hasAttachment
                                            ? route('downloadable-modules.download', $downloadable->id)
                                            : '#';
                        @endphp
                        <li class="flex flex-col items-center gap-y-2">
                            <a href="{{ $downloadUrl }}"
                               @if($hasAttachment) target="_blank"
                               @else onclick="return false;" @endif
                               class="w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24
                                      rounded-lg flex items-center justify-center
                                      shadow-sm ring-1 ring-gray-950/10
                                      transition-all duration-150 focus:outline-none
                                      focus-visible:ring-2 focus-visible:ring-orange-600
                                      {{ $hasAttachment
                                            ? 'bg-orange-600 hover:bg-orange-500 text-white cursor-pointer'
                                            : 'bg-gray-50 hover:bg-gray-100 text-gray-500 cursor-not-allowed' }}"
                               title="{{ $hasAttachment ? 'Download '.$downloadable->form_title : 'No attachment available' }}">
                                @svg($iconName, 'w-7 h-7 sm:w-9 sm:h-9 md:w-10 md:h-10 current-color')
                            </a>
                            <span class="text-gray-500 text-center text-xs leading-tight">
                                {{ $downloadable->form_title ?? 'Untitled' }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="flex items-center justify-center h-28 rounded-xl bg-gray-50 ring-1 ring-gray-950/5">
                    <p class="text-gray-500 text-sm">No downloadable forms available.</p>
                </div>
            @endif
        </x-portal.card>

        {{-- ── WORKFLOWS  &  FAQ  ───────────────────────────────── --}}
        <div class="grid grid-cols-1 md:grid-cols-[55fr_45fr] gap-6">

            {{-- Workflows --}}
            <x-portal.card padding="p-4 sm:p-6" class="flex flex-col">
                <h2 class="font-semibold text-gray-950 text-lg sm:text-xl md:text-2xl leading-tight tracking-tight mb-4">
                    Workflows &amp; Forms
                </h2>

                @if ($department->workflows->count() > 0)
                    <div class="flex flex-col gap-y-2 flex-1">
                        @foreach ($department->workflows as $workflow)
                            <details class="group rounded-lg bg-white shadow-sm ring-1 ring-gray-950/5 overflow-hidden">
                                <summary class="flex w-full cursor-pointer select-none items-center
                                                justify-between gap-3 px-4 py-3.5 font-medium text-gray-950
                                                hover:bg-gray-50 transition-colors duration-150">
                                    <span class="text-sm sm:text-base leading-snug">
                                        {{ $workflow->workflow_title }}
                                    </span>
                                    {{-- Plus icon --}}
                                    <svg class="icon-plus h-4 w-4 shrink-0 text-gray-400"
                                         xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                    </svg>
                                    {{-- Minus icon --}}
                                    <svg class="icon-minus h-4 w-4 shrink-0 text-gray-400"
                                         xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15"/>
                                    </svg>
                                </summary>
                                <div class="accordion-body px-4 pt-3 pb-4 border-t border-gray-200">
                                    <div class="text-gray-500 text-sm leading-relaxed mb-3 prose prose-sm max-w-none">
                                        {!! Str::limit(strip_tags($workflow->workflow_body), 200) !!}
                                    </div>
                                    @if ($workflow->tag)
                                        <span class="inline-flex items-center rounded-md ring-1 ring-inset bg-gray-50 text-gray-600 ring-gray-600/10 px-2 py-1 text-xs font-medium">
                                            {{ $workflow->tag->workflow_tag_name }}
                                        </span>
                                    @endif
                                </div>
                            </details>
                        @endforeach
                    </div>

                    <div class="mt-5 pt-1">
                        <x-portal.button :href="route('workflow.index', ['department' => $department->cms_department_slug])">
                            Find out more
                            <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                            </svg>
                        </x-portal.button>
                    </div>
                @else
                    <div class="flex items-center justify-center flex-1 h-28 rounded-xl bg-gray-50 ring-1 ring-gray-950/5">
                        <p class="text-gray-500 text-sm">No workflows available for this department.</p>
                    </div>
                @endif
            </x-portal.card>

            {{-- FAQ --}}
            <x-portal.card padding="p-4 sm:p-6" class="flex flex-col">
                <h2 class="font-semibold text-gray-950 text-lg sm:text-xl md:text-2xl leading-tight tracking-tight mb-4">
                    Got Questions?
                </h2>

                @if ($department->faqs->count() > 0)
                    <div class="flex flex-col gap-y-2 flex-1">
                        @foreach ($department->faqs as $faq)
                            <details class="group rounded-lg bg-white shadow-sm ring-1 ring-gray-950/5 overflow-hidden">
                                <summary class="flex w-full cursor-pointer select-none items-center
                                                justify-between gap-3 px-4 py-3.5 font-medium text-gray-950
                                                hover:bg-gray-50 transition-colors duration-150">
                                    <span class="text-sm sm:text-base leading-snug">
                                        {{ $faq->faq_title }}
                                    </span>
                                    <svg class="icon-plus h-4 w-4 shrink-0 text-gray-400"
                                         xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                    </svg>
                                    <svg class="icon-minus h-4 w-4 shrink-0 text-gray-400"
                                         xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15"/>
                                    </svg>
                                </summary>
                                <div class="accordion-body px-4 pt-3 pb-4 border-t border-gray-200">
                                    <div class="text-gray-500 text-sm leading-relaxed mb-3 prose prose-sm max-w-none">
                                        {!! $faq->faq_body !!}
                                    </div>
                                    @if ($faq->tag)
                                        <span class="inline-flex items-center rounded-md ring-1 ring-inset bg-gray-50 text-gray-600 ring-gray-600/10 px-2 py-1 text-xs font-medium">
                                            {{ $faq->tag->faq_tag_name }}
                                        </span>
                                    @endif
                                </div>
                            </details>
                        @endforeach
                    </div>

                    <div class="mt-5 pt-1">
                        <x-portal.button :href="route('faq.index', ['department' => $department->cms_department_slug])">
                            Read more
                            <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                            </svg>
                        </x-portal.button>
                    </div>
                @else
                    <div class="flex items-center justify-center flex-1 h-28 rounded-xl bg-gray-50 ring-1 ring-gray-950/5">
                        <p class="text-gray-500 text-sm">No FAQs available for this department.</p>
                    </div>
                @endif
            </x-portal.card>
        </div>

        {{-- ── DIRECTORY ─────────────────────────────────────────── --}}
        <section class="mt-2">
            {{-- Section heading --}}
            <div class="mb-6 flex items-center gap-3">
                <h2 class="font-semibold text-gray-950 text-2xl sm:text-3xl md:text-4xl
                            leading-none tracking-tight">
                    Directory
                </h2>
            </div>

            @if($department->directories->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-5">
                    @foreach($department->directories as $directory)
                        <div class="rounded-xl overflow-hidden aspect-portrait relative bg-gray-200 shadow-sm ring-1 ring-gray-950/5">
                            @if($directory->poc_image)
                                <img class="absolute inset-0 h-full w-full object-cover"
                                     src="{{ $directory->image_url }}"
                                     alt="{{ $directory->employee?->full_name ?? 'Profile' }}"/>
                            @else
                                {{-- Placeholder --}}
                                <div class="absolute inset-0 bg-gray-200 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="w-16 h-16 text-gray-400"
                                         fill="none" viewBox="0 0 24 24"
                                         stroke="currentColor" stroke-width="1">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                    </svg>
                                </div>
                            @endif

                            {{-- Gradient + text overlay --}}
                            <div class="absolute inset-0 bg-gradient-to-t from-gray-950/75 via-gray-950/10 to-transparent"></div>
                            <div class="absolute bottom-0 w-full px-3 pb-4 text-center">
                                <p class="text-white font-semibold text-base sm:text-lg leading-tight line-clamp-2">
                                    {{ $directory->employee?->full_name ?? 'Unknown' }}
                                </p>
                                <p class="text-white/70 text-xs font-medium uppercase tracking-wide mt-0.5 line-clamp-1">
                                    {{ $directory->poc_job_position ?? 'No Position' }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex items-center justify-center h-48 rounded-xl bg-gray-50 ring-1 ring-gray-950/5">
                    <p class="text-gray-500 text-sm">No directory entries available for this department.</p>
                </div>
            @endif
        </section>

    </main>

    {{-- ═══════════════════ SCRIPTS ═══════════════════ --}}
    <script>
        // Log downloadable clicks to server (non-blocking)
        (function () {
            const token = '{{ csrf_token() }}';
            document.addEventListener('click', function (e) {
                const a = e.target.closest && e.target.closest('a.js-log-download');
                if (!a) return;
                const id = a.dataset.downloadableId;
                if (!id) return;
                fetch('{{ route('downloadable.log') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ downloadable_id: id }),
                }).catch(() => {});
            });
        })();
    </script>
</x-portal.layout>
