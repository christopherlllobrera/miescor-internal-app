<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $department->display_name }} - MIESCOR</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/miescor/favicon.ico') }}">
    <meta name="description" content="MIESCOR is a leading engineering and construction company in the Philippines.">
    <meta name="keywords" content="MIESCOR, Engineering, Construction, {{ $department->display_name }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Geist font (falls back to Inter) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        /* ── Design-system tokens ───────────────────────────────── */
        :root {
            --color-canvas-white:  #ffffff;
            --color-ghost-gray:    #f2f2f2;
            --color-subtle-ash:    #e5e5e5;
            --color-midtone-gray:  #737373;
            --color-rich-black:    #0a0a0a;
            --color-deep-black:    #000000;
            --color-callout-red:   #c22b10;
            --color-success-green: #10c22b;
            --font-geist: 'Geist', 'Inter', ui-sans-serif, system-ui, sans-serif;
            --shadow-subtle-2: oklab(0.145 -0.00000143796 0.00000340492 / 0.1) 0px 0px 0px 1px;
            --radius-cards:   14px;
            --radius-buttons: 10px;
            --radius-badge:   26px;
            --radius-pill:    9999px;
        }
        body { font-family: var(--font-geist); }

        /* ── Elevated card shadow ───────────────────────────────── */
        .card-shadow { box-shadow: var(--shadow-subtle-2); }

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

        /* ── Tag badge ──────────────────────────────────────────── */
        .badge-neutral {
            background: var(--color-ghost-gray);
            color: var(--color-rich-black);
            border-radius: var(--radius-badge);
            padding: 2px 8px;
            font-size: 12px;
            font-weight: 500;
            letter-spacing: 0;
        }
        .badge-inverse {
            background: var(--color-deep-black);
            color: var(--color-canvas-white);
            border-radius: var(--radius-badge);
            padding: 2px 8px;
            font-size: 12px;
            font-weight: 500;
        }
    </style>
</head>

<body class="bg-[#f2f2f2] text-[#0a0a0a] antialiased">

    {{-- ═══════════════════ HEADER / NAV ═══════════════════ --}}
    <header class="fixed w-full top-0 z-50 bg-white transition-transform duration-300 shadow-md">
        <nav class="px-4 md:px-6 py-3 relative">
            <div class="flex justify-between items-center mx-auto max-w-screen-4xl relative">
                <a href="/" rel="noreferrer" class="flex items-center z-10">
                    <img src="{!! URL('images/logo/miescor_light_mode.png') !!}"
                        class="h-10 sm:h-12 lg:h-14" alt="Miescor Logo" />
                </a>
            </div>
        </nav>
    </header>

    {{-- ═══════════════════ MAIN ═══════════════════ --}}
    <main class="pt-20 sm:pt-24 max-w-screen-2xl mx-auto px-4 md:px-8 space-y-6 pb-16">

        {{-- ── HERO BANNER ─────────────────────────────────────── --}}
        <section class="mt-6 relative rounded-[14px] overflow-hidden h-64 sm:h-80 md:h-96 bg-[#0a0a0a]">
            <img src="{{ $department->image_url }}"
                 alt="{{ $department->cms_department_name }} Cover"
                 class="absolute inset-0 w-full h-full object-cover">
            {{-- Gradient overlay --}}
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>
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
        <section class="bg-[#ffffff] rounded-[14px] card-shadow p-4 sm:p-6">
            <h2 class="font-semibold text-[#000000] text-lg sm:text-xl md:text-2xl leading-tight tracking-[-0.45px] mb-4 sm:mb-5">
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
                                      rounded-[10px] flex items-center justify-center
                                      transition-all duration-150 focus:outline-none
                                      focus:ring-2 focus:ring-[#0a0a0a] focus:ring-offset-2
                                      {{ $hasAttachment
                                            ? 'bg-[#ea580c] hover:bg-[#c2410c] cursor-pointer'
                                            : 'bg-[#e5e5e5] cursor-not-allowed' }}"
                               title="{{ $hasAttachment ? 'Download '.$downloadable->form_title : 'No attachment available' }}">
                                @svg($iconName, 'w-7 h-7 sm:w-9 sm:h-9 md:w-10 md:h-10 text-white')
                            </a>
                            <span class="text-[#737373] text-center text-[11px] sm:text-xs leading-tight">
                                {{ $downloadable->form_title ?? 'Untitled' }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="flex items-center justify-center h-28 rounded-[10px] bg-[#f2f2f2] border border-[#e5e5e5]">
                    <p class="text-[#737373] text-sm">No downloadable forms available.</p>
                </div>
            @endif
        </section>

        {{-- ── WORKFLOWS  &  FAQ  ───────────────────────────────── --}}
        <div class="grid grid-cols-1 md:grid-cols-[55fr_45fr] gap-6">

            {{-- Workflows --}}
            <section class="bg-[#ffffff] rounded-[14px] card-shadow p-4 sm:p-6 flex flex-col">
                <h2 class="font-semibold text-[#000000] text-lg sm:text-xl md:text-2xl leading-tight tracking-[-0.45px] mb-4">
                    Workflows &amp; Forms
                </h2>

                @if ($department->workflows->count() > 0)
                    <div class="flex flex-col gap-y-2 flex-1">
                        @foreach ($department->workflows as $workflow)
                            <details class="group rounded-[10px] border border-[#e5e5e5] bg-[#ffffff] overflow-hidden">
                                <summary class="flex w-full cursor-pointer select-none items-center
                                                justify-between gap-3 px-4 py-3.5 font-medium text-[#0a0a0a]
                                                hover:bg-[#f2f2f2] transition-colors duration-150">
                                    <span class="text-sm sm:text-base leading-snug">
                                        {{ $workflow->workflow_title }}
                                    </span>
                                    {{-- Plus icon --}}
                                    <svg class="icon-plus h-4 w-4 shrink-0 text-[#737373]"
                                         xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                    </svg>
                                    {{-- Minus icon --}}
                                    <svg class="icon-minus h-4 w-4 shrink-0 text-[#737373]"
                                         xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15"/>
                                    </svg>
                                </summary>
                                <div class="accordion-body px-4 pt-3 pb-4 border-t border-[#e5e5e5]">
                                    <div class="text-[#737373] text-sm leading-relaxed mb-3 prose prose-sm max-w-none">
                                        {!! Str::limit(strip_tags($workflow->workflow_body), 200) !!}
                                    </div>
                                    @if ($workflow->tag)
                                        <span class="badge-neutral">
                                            {{ $workflow->tag->workflow_tag_name }}
                                        </span>
                                    @endif
                                </div>
                            </details>
                        @endforeach
                    </div>

                    <div class="mt-5 pt-1">
                        <a href="{{ route('workflow.index', ['department' => $department->cms_department_slug]) }}"
                           class="inline-flex items-center gap-1.5
                                  rounded-[10px] bg-[#ea580c] px-4 py-2
                                  text-sm font-medium text-[#ffffff]
                                  hover:bg-[#c2410c] transition-colors duration-150
                                  focus:outline-none focus:ring-2 focus:ring-[#0a0a0a] focus:ring-offset-2">
                            Find out more
                            <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                            </svg>
                        </a>
                    </div>
                @else
                    <div class="flex items-center justify-center flex-1 h-28 rounded-[10px] bg-[#f2f2f2] border border-[#e5e5e5]">
                        <p class="text-[#737373] text-sm">No workflows available for this department.</p>
                    </div>
                @endif
            </section>

            {{-- FAQ --}}
            <section class="bg-[#ffffff] rounded-[14px] card-shadow p-4 sm:p-6 flex flex-col">
                <h2 class="font-semibold text-[#000000] text-lg sm:text-xl md:text-2xl leading-tight tracking-[-0.45px] mb-4">
                    Got Questions?
                </h2>

                @if ($department->faqs->count() > 0)
                    <div class="flex flex-col gap-y-2 flex-1">
                        @foreach ($department->faqs as $faq)
                            <details class="group rounded-[10px] border border-[#e5e5e5] bg-[#ffffff] overflow-hidden">
                                <summary class="flex w-full cursor-pointer select-none items-center
                                                justify-between gap-3 px-4 py-3.5 font-medium text-[#0a0a0a]
                                                hover:bg-[#f2f2f2] transition-colors duration-150">
                                    <span class="text-sm sm:text-base leading-snug text-[#0a0a0a]">
                                        {{ $faq->faq_title }}
                                    </span>
                                    <svg class="icon-plus h-4 w-4 shrink-0 text-[#737373]"
                                         xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                    </svg>
                                    <svg class="icon-minus h-4 w-4 shrink-0 text-[#737373]"
                                         xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15"/>
                                    </svg>
                                </summary>
                                <div class="accordion-body px-4 pt-3 pb-4 border-t border-[#e5e5e5]">
                                    <div class="text-[#737373] text-sm leading-relaxed mb-3 prose prose-sm max-w-none">
                                        {!! $faq->faq_body !!}
                                    </div>
                                    @if ($faq->tag)
                                        <span class="badge-neutral">
                                            {{ $faq->tag->faq_tag_name }}
                                        </span>
                                    @endif
                                </div>
                            </details>
                        @endforeach
                    </div>

                    <div class="mt-5 pt-1">
                        <a href="{{ route('faq.index', ['department' => $department->cms_department_slug]) }}"
                           class="inline-flex items-center gap-1.5
                                  rounded-[10px] bg-[#ea580c] px-4 py-2
                                  text-sm font-medium text-[#ffffff]
                                  hover:bg-[#c2410c] transition-colors duration-150
                                  focus:outline-none focus:ring-2 focus:ring-[#c2410c] focus:ring-offset-2">
                            Read more
                            <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                            </svg>
                        </a>
                    </div>
                @else
                    <div class="flex items-center justify-center flex-1 h-28 rounded-[10px] bg-[#f2f2f2] border border-[#e5e5e5]">
                        <p class="text-[#737373] text-sm">No FAQs available for this department.</p>
                    </div>
                @endif
            </section>
        </div>

        {{-- ── DIRECTORY ─────────────────────────────────────────── --}}
        <section class="mt-2">
            {{-- Section heading --}}
            <div class="mb-6 flex items-center gap-3">
                <h2 class="font-semibold text-[#000000] text-2xl sm:text-3xl md:text-4xl
                            leading-none tracking-[-0.045em]">
                    Directory
                </h2>
            </div>

            @if($department->directories->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-5">
                    @foreach($department->directories as $directory)
                        <div class="rounded-[14px] overflow-hidden aspect-portrait relative bg-[#e5e5e5] card-shadow">
                            @if($directory->poc_image)
                                <img class="absolute inset-0 h-full w-full object-cover"
                                     src="{{ $directory->image_url }}"
                                     alt="{{ $directory->employee?->full_name ?? 'Profile' }}"/>
                            @else
                                {{-- Placeholder --}}
                                <div class="absolute inset-0 bg-[#e5e5e5] flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="w-16 h-16 text-[#737373]"
                                         fill="none" viewBox="0 0 24 24"
                                         stroke="currentColor" stroke-width="1">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                    </svg>
                                </div>
                            @endif

                            {{-- Gradient + text overlay --}}
                            <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/10 to-transparent"></div>
                            <div class="absolute bottom-0 w-full px-3 pb-4 text-center">
                                <p class="text-[#ffffff] font-semibold text-base sm:text-lg leading-tight line-clamp-2">
                                    {{ $directory->employee?->full_name ?? 'Unknown' }}
                                </p>
                                <p class="text-[#ffffff]/70 text-[11px] sm:text-xs font-medium uppercase tracking-wide mt-0.5 line-clamp-1">
                                    {{ $directory->poc_job_position ?? 'No Position' }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex items-center justify-center h-48 rounded-[14px] bg-[#f2f2f2] border border-[#e5e5e5]">
                    <p class="text-[#737373] text-sm">No directory entries available for this department.</p>
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

</body>
</html>
