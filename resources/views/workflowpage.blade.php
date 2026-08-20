<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $currentDepartment->display_name }}- Workflow - I AM MIESCOR</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/miescor/favicon.ico') }}">
    <meta name="description" content="MIESCOR is a leading engineering and construction company in the Philippines.">
    <meta name="keywords" content="MIESCOR, Engineering, Construction, Philippines">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white min-h-screen">
    <header class="fixed w-full top-0 z-50 bg-white transition-transform duration-300 shadow-md">
        <nav class="px-4 md:px-6 py-3 relative">
            <div class="flex justify-between items-center mx-auto max-w-screen-4xl relative">
                <a href="/" rel="noreferrer" class="flex items-center z-10">
                    <img src="{!! URL('images/logo/miescor_light_mode.png') !!}" class="h-10 sm:h-12 lg:h-14" alt="Miescor Logo" />
                </a>
                <!-- Search (commented out to match FAQ page) -->
                {{-- <!-- Search -->
                <div class="hidden ml-auto mr-4 sm:inline-flex">
                    <div class="relative w-64">
                        <input type="text" placeholder="Type to search"
                            class="w-full h-10 rounded-lg border border-[#e5e7eb] bg-white pl-4 pr-11 text-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-500 focus:outline-none" />
                        <div class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-gray-[#9ca3af]">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                        </div>
                    </div>
                </div> --}}
            </div>
        </nav>
    </header>

    <main class="pt-20 sm:pt-24 lg:pt-28">
        <!-- Banner -->
        <div class="mx-4 sm:mx-6 lg:mx-8 mt-4 sm:mt-6 lg:mt-8">
            <div class="relative h-48 sm:h-56 md:h-64 lg:h-72 rounded-xl bg-linear-to-r from-orange-500 to-amber-400 overflow-hidden">
                <div class="absolute inset-0 flex flex-col justify-center p-6 sm:p-8 lg:p-12">
                    <h1 class="text-white font-semibold text-xl sm:text-2xl md:text-3xl lg:text-4xl">
                        Workflows & Forms
                    </h1>
                    <p class="text-white/80 text-sm sm:text-base max-w-md mt-3 hidden sm:block">
                        Step-by-step guides to help you navigate {{ $currentDepartment->display_name }} processes.
                    </p>
                    {{-- <div class="mt-4 sm:mt-6">
                        <button type="button" class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2.5 text-sm font-medium text-gray-900 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-orange-500 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z" />
                            </svg>
                            <span>Need help?</span>
                        </button>
                    </div> --}}
                </div>
            </div>
        </div>

        <!-- Content Container -->
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 sm:py-10 lg:py-12">
            <div class="flex flex-col lg:flex-row gap-8 lg:gap-12">

                <!-- SIDENAV -->
                <aside class="lg:w-64 shrink-0" aria-label="Workflow Categories">
                    <nav class="lg:sticky lg:top-28 space-y-1">
                        <p class="text-xs font-semibold text-[#374151] uppercase tracking-wider mb-3">Categories</p>

                        @foreach($tags as $tag)
                            <a href="#{{ Str::slug($tag->workflow_tag_name) }}"
                                class="block px-3 py-2 rounded-lg text-sm font-medium text-[#374151] hover:bg-orange-50 hover:text-orange-600 transition-colors">
                                {{ $tag->workflow_tag_name }}
                            </a>
                        @endforeach

                        <hr class="my-4 border-[#e5e7eb]">

                        <a href="{{ route('department.show', $currentDepartment->cms_department_slug) }}"
                            class="inline-flex items-center gap-1 px-3 py-2 text-sm text-[#374151] hover:text-[#374151] transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                            </svg>
                            Back to {{ $currentDepartment->display_name }}
                        </a>
                    </nav>
                </aside>

                <!-- MAIN CONTENT -->
                <section class="flex-1 min-w-0">
                    <!-- SEARCH BAR -->
                    <div class="mb-8">
                        <form action="{{ route('workflow.index', ['department' => $currentDepartment->cms_department_slug]) }}" method="GET" class="max-w-lg">
                            <label for="workflow-search" class="sr-only">Search Workflows</label>
                            <div class="relative">
                                <input id="workflow-search" type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Search workflows..."
                                    class="w-full py-3 pl-4 pr-12 border border-[#e5e7eb] rounded-lg bg-white text-[#111827] placeholder-[#6b7280] focus:ring-2 focus:ring-orange-500 focus:border-orange-500 focus:outline-none text-sm">
                                <button type="submit" class="absolute right-1 top-1 bottom-1 px-3 rounded-md transition-colors">
                                    <svg class="w-5 h-5 text-[#6b7280]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35m0 0A7.5 7.5 0 1 0 5 5a7.5 7.5 0 0 0 11.65 11.65Z" />
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>

                    @if($workflows->count() > 0)
                        <div class="space-y-10">
                            @foreach($workflowsByTag as $tagName => $tagWorkflows)
                                <!-- WORKFLOW CATEGORY -->
                                <div id="{{ Str::slug($tagName) }}" class="scroll-mt-24">
                                    <h2 class="text-lg sm:text-xl lg:text-2xl font-semibold text-[#111827] mb-4 pb-2 border-b border-[#f3f4f6]">
                                        {{ $tagName }}
                                    </h2>

                                    <div class="space-y-0 divide-y divide-[#f3f4f6]">
                                        @foreach($tagWorkflows as $workflow)
                                            <details class="group py-4" id="workflow-{{ $workflow->id }}">
                                                <summary class="cursor-pointer flex justify-between items-start gap-4 text-[#374151] font-medium text-sm sm:text-base list-none">
                                                    <span class="flex-1">{{ $workflow->workflow_title }}</span>
                                                    <span class="shrink-0 w-6 h-6 rounded-full bg-[#f3f4f6] group-open:bg-orange-100 flex items-center justify-center transition-colors">
                                                        <svg class="w-4 h-4 text-[#374151] group-open:text-orange-500 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                        </svg>
                                                    </span>
                                                </summary>

                                                <!-- Workflow Body -->
                                                <div class="mt-3 pr-10 overflow-hidden">
                                                    <div class="workflow-content text-sm text-[#374151] leading-relaxed">
                                                        {!! $workflow->workflow_body !!}
                                                    </div>
                                                </div>
                                            </details>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- NO Workflows -->
                        <div class="flex flex-col items-center justify-center py-16 text-center">
                            <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-[#9ca3af]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-[#111827] mb-2">No Workflows found</h3>
                            <p class="text-[#374151] text-sm max-w-sm">
                                @if(request('search'))
                                    No results found for "{{ request('search') }}". Try a different search term.
                                @else
                                    There are no workflows available for this department yet.
                                @endif
                            </p>
                            @if(request('search'))
                                <a href="{{ route('workflow.index', ['department' => $currentDepartment->cms_department_slug]) }}"
                                    class="mt-4 inline-flex items-center gap-1 text-orange-600 hover:text-orange-700 font-medium text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                    </svg>
                                    Clear search
                                </a>
                            @endif
                        </div>
                    @endif
                </section>
            </div>
        </div>
    </main>

    <style>
        /* Remove default details marker */
        details summary::-webkit-details-marker,
        details summary::marker {
            display: none;
        }

        /* Workflow Content Styles - Constrain all content */
        .workflow-content {
            max-width: 100%;
            overflow-wrap: break-word;
            word-wrap: break-word;
            word-break: break-word;
        }

        .workflow-content * {
            max-width: 100%;
            font-size: 0.875rem;
            line-height: 1.625;
        }

        .workflow-content p {
            margin-bottom: 0.75rem;
        }

        .workflow-content p:last-child {
            margin-bottom: 0;
        }

        .workflow-content ul,
        .workflow-content ol {
            margin-bottom: 0.75rem;
            padding-left: 1.5rem;
        }

        .workflow-content ul {
            list-style-type: disc;
        }

        .workflow-content ol {
            list-style-type: decimal;
        }

        .workflow-content li {
            margin-bottom: 0.25rem;
        }

        .workflow-content h1,
        .workflow-content h2,
        .workflow-content h3,
        .workflow-content h4 {
            font-size: 0.9375rem;
            font-weight: 600;
            margin-top: 1rem;
            margin-bottom: 0.5rem;
        }

        .workflow-content h1:first-child,
        .workflow-content h2:first-child,
        .workflow-content h3:first-child {
            margin-top: 0;
        }

        .workflow-content img {
            max-width: 100%;
            height: auto;
            border-radius: 0.5rem;
            margin: 0.75rem 0;
        }

        .workflow-content table {
            width: 100%;
            border-collapse: collapse;
            margin: 0.75rem 0;
            font-size: 0.875rem;
        }

        .workflow-content th,
        .workflow-content td {
            border: 1px solid #e5e7eb;
            padding: 0.5rem;
            text-align: left;
        }

        .workflow-content a {
            color: #ea580c;
            text-decoration: underline;
        }

        .workflow-content a:hover {
            color: #c2410c;
        }

        .workflow-content pre,
        .workflow-content code {
            font-size: 0.8125rem;
            background-color: #f3f4f6;
            border-radius: 0.25rem;
            overflow-x: auto;
        }

        .workflow-content pre {
            padding: 0.75rem;
            margin: 0.75rem 0;
        }

        .workflow-content code {
            padding: 0.125rem 0.25rem;
        }

        .workflow-content blockquote {
            border-left: 3px solid #ea580c;
            padding-left: 1rem;
            margin: 0.75rem 0;
            color: #4b5563;
            font-style: italic;
        }
    </style>
</body>

</html>
