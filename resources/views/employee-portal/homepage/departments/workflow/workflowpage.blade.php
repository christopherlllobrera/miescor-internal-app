<x-portal.layout
    :title="$currentDepartment->display_name . ' - Workflow - I AM MIESCOR'"
    :description="'MIESCOR is a leading engineering and construction company in the Philippines.'"
    :keywords="'MIESCOR, Engineering, Construction, Philippines'"
>
    <x-slot:head>
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
                border: 1px solid var(--color-gray-200, #e5e7eb);
                padding: 0.5rem;
                text-align: left;
            }

            .workflow-content a {
                color: var(--color-orange-600, #ea580c);
                text-decoration: underline;
            }

            .workflow-content a:hover {
                color: var(--color-orange-500, #f97316);
            }

            .workflow-content pre,
            .workflow-content code {
                font-size: 0.8125rem;
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
                border-left: 3px solid var(--color-orange-600, #ea580c);
                padding-left: 1rem;
                margin: 0.75rem 0;
                font-style: italic;
            }
        </style>
    </x-slot:head>

    <x-portal.navigation :minimal="true" />

    <main class="pt-20 sm:pt-24 max-w-screen-2xl mx-auto px-4 md:px-8 space-y-6 pb-16">
        {{-- ── HERO BANNER ─────────────────────────────────────── --}}
        <section class="mt-6 relative rounded-xl overflow-hidden h-64 sm:h-80 md:h-96 bg-linear-to-r from-orange-500 to-orange-400 shadow-sm">
            <div class="absolute inset-0 flex flex-col justify-end p-6 sm:p-8 md:p-10">
                <h1 class="text-white font-semibold text-2xl sm:text-3xl md:text-4xl leading-tight tracking-tight">
                    Workflows & Forms
                </h1>
                <p class="mt-2 text-white/80 text-sm sm:text-base leading-relaxed max-w-3xl line-clamp-3 sm:line-clamp-none hidden sm:block">
                    Step-by-step guides to help you navigate {{ $currentDepartment->display_name }} processes.
                </p>
            </div>
        </section>

        <!-- Content Container -->
        <div class="flex flex-col lg:flex-row gap-8 lg:gap-12 mt-6">

            <!-- SIDENAV -->
            <aside class="lg:w-64 shrink-0" aria-label="Workflow Categories">
                <nav class="lg:sticky lg:top-28 space-y-1">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Categories</p>

                    @foreach($tags as $tag)
                        <a href="#{{ Str::slug($tag->workflow_tag_name) }}"
                            class="block px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-orange-700 transition-colors">
                            {{ $tag->workflow_tag_name }}
                        </a>
                    @endforeach

                    <hr class="my-4 border-gray-200">

                    <a href="{{ route('department.show', $currentDepartment->cms_department_slug) }}"
                        class="inline-flex items-center gap-1 px-3 py-2 text-sm font-medium text-gray-700 hover:text-orange-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                        </svg>
                        Back to {{ $currentDepartment->display_name }}
                    </a>
                </nav>
            </aside>

            <!-- MAIN CONTENT -->
            <section class="flex-1 min-w-0 bg-white p-6 rounded-xl shadow-sm ring-1 ring-gray-950/5">
                <!-- SEARCH BAR -->
                <div class="mb-8">
                    <form action="{{ route('workflow.index', ['department' => $currentDepartment->cms_department_slug]) }}" method="GET" class="max-w-lg">
                        <label for="workflow-search" class="sr-only">Search Workflows</label>
                        <div class="relative">
                            <input id="workflow-search" type="text" name="search" value="{{ request('search') }}"
                                placeholder="Search workflows..."
                                class="w-full py-2.5 pl-4 pr-12 rounded-lg bg-white text-gray-950 placeholder-gray-400 shadow-sm ring-1 ring-gray-950/10 focus:ring-2 focus:ring-orange-600 focus:outline-none text-sm border-none">
                            <button type="submit" class="absolute right-1 top-1 bottom-1 px-3 rounded-md transition-colors text-gray-500 hover:text-gray-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-orange-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                <h2 class="text-base sm:text-lg lg:text-xl font-semibold text-gray-950 mb-4 pb-2 border-b border-gray-200">
                                    {{ $tagName }}
                                </h2>

                                <div class="space-y-0 divide-y divide-gray-200">
                                    @foreach($tagWorkflows as $workflow)
                                        <details class="group py-4" id="workflow-{{ $workflow->id }}">
                                            <summary class="cursor-pointer flex justify-between items-start gap-4 text-gray-700 font-medium text-sm sm:text-base list-none hover:text-orange-700 transition-colors">
                                                <span class="flex-1">{{ $workflow->workflow_title }}</span>
                                                <span class="shrink-0 w-6 h-6 rounded-full bg-gray-100 group-open:bg-orange-50 flex items-center justify-center transition-colors">
                                                    <svg class="w-4 h-4 text-gray-500 group-open:text-orange-600 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                    </svg>
                                                </span>
                                            </summary>

                                            <!-- Workflow Body -->
                                            <div class="mt-3 pr-10 overflow-hidden">
                                                <div class="workflow-content text-sm text-gray-600 leading-relaxed">
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
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" />
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold text-gray-950 mb-2">No Workflows found</h3>
                        <p class="text-gray-500 text-sm max-w-sm">
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
    </main>
</x-portal.layout>
