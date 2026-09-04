<x-portal.layout
    :title="$currentDepartment->display_name . ' - FAQ - I AM MIESCOR'"
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

            /* FAQ Content Styles - Constrain all content */
            .faq-content {
                max-width: 100%;
                overflow-wrap: break-word;
                word-wrap: break-word;
                word-break: break-word;
            }

            .faq-content * {
                max-width: 100%;
                font-size: 0.875rem;
                line-height: 1.625;
            }

            .faq-content p {
                margin-bottom: 0.75rem;
            }

            .faq-content p:last-child {
                margin-bottom: 0;
            }

            .faq-content ul,
            .faq-content ol {
                margin-bottom: 0.75rem;
                padding-left: 1.5rem;
            }

            .faq-content ul {
                list-style-type: disc;
            }

            .faq-content ol {
                list-style-type: decimal;
            }

            .faq-content li {
                margin-bottom: 0.25rem;
            }

            .faq-content h1,
            .faq-content h2,
            .faq-content h3,
            .faq-content h4 {
                font-size: 0.9375rem;
                font-weight: 600;
                margin-top: 1rem;
                margin-bottom: 0.5rem;
            }

            .faq-content h1:first-child,
            .faq-content h2:first-child,
            .faq-content h3:first-child {
                margin-top: 0;
            }

            .faq-content img {
                max-width: 100%;
                height: auto;
                border-radius: 0.5rem;
                margin: 0.75rem 0;
            }

            .faq-content table {
                width: 100%;
                border-collapse: collapse;
                margin: 0.75rem 0;
                font-size: 0.875rem;
            }

            .faq-content th,
            .faq-content td {
                border: 1px solid var(--color-gray-200, #e5e7eb);
                padding: 0.5rem;
                text-align: left;
            }

            .faq-content a {
                color: var(--color-orange-600, #ea580c);
                text-decoration: underline;
            }

            .faq-content a:hover {
                color: var(--color-orange-500, #f97316);
            }

            .faq-content pre,
            .faq-content code {
                font-size: 0.8125rem;
                border-radius: 0.25rem;
                overflow-x: auto;
            }

            .faq-content pre {
                padding: 0.75rem;
                margin: 0.75rem 0;
            }

            .faq-content code {
                padding: 0.125rem 0.25rem;
            }

            .faq-content blockquote {
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
                    Frequently Asked Questions
                </h1>
                <p class="mt-2 text-white/80 text-sm sm:text-base leading-relaxed max-w-3xl line-clamp-3 sm:line-clamp-none hidden sm:block">
                    Can't find what you're looking for? Submit your question!
                </p>
                <div class="mt-4 sm:mt-6">
                    <button type="button" class="inline-flex items-center gap-1.5 rounded-lg bg-white px-4 py-2 text-sm font-medium text-gray-950 shadow-sm ring-1 ring-gray-950/5 hover:bg-gray-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-orange-600 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                        </svg>
                        <span>Ask our team</span>
                    </button>
                </div>
            </div>
        </section>

        <!-- Content Container -->
        <div class="flex flex-col lg:flex-row gap-8 lg:gap-12 mt-6">

            <!-- SIDENAV -->
            <aside class="lg:w-64 shrink-0" aria-label="FAQ Categories">
                <nav class="lg:sticky lg:top-28 space-y-1">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Categories</p>

                    @foreach($tags as $tag)
                        <a href="#{{ Str::slug($tag->faq_tag_name) }}"
                            class="block px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-orange-700 transition-colors">
                            {{ $tag->faq_tag_name }}
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
                    <form action="{{ route('faq.index', ['department' => $currentDepartment->cms_department_slug]) }}" method="GET" class="max-w-lg">
                        <label for="faq-search" class="sr-only">Search FAQ</label>
                        <div class="relative">
                            <input id="faq-search" type="text" name="search" value="{{ request('search') }}"
                                placeholder="Search FAQs..."
                                class="w-full py-2.5 pl-4 pr-12 rounded-lg bg-white text-gray-950 placeholder-gray-400 shadow-sm ring-1 ring-gray-950/10 focus:ring-2 focus:ring-orange-600 focus:outline-none text-sm border-none">
                            <button type="submit" class="absolute right-1 top-1 bottom-1 px-3 rounded-md transition-colors text-gray-500 hover:text-gray-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-orange-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35m0 0A7.5 7.5 0 1 0 5 5a7.5 7.5 0 0 0 11.65 11.65Z" />
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>

                @if($faqs->count() > 0)
                    <div class="space-y-10">
                        @foreach($faqsByTag as $tagName => $tagFaqs)
                            <!-- FAQ CATEGORY -->
                            <div id="{{ Str::slug($tagName) }}" class="scroll-mt-24">
                                <h2 class="text-base sm:text-lg lg:text-xl font-semibold text-gray-950 mb-4 pb-2 border-b border-gray-200">
                                    {{ $tagName }}
                                </h2>

                                <div class="space-y-0 divide-y divide-gray-200">
                                    @foreach($tagFaqs as $faq)
                                        <details class="group py-4" id="faq-{{ $faq->id }}">
                                            <summary class="cursor-pointer flex justify-between items-start gap-4 text-gray-700 font-medium text-sm sm:text-base list-none hover:text-orange-700 transition-colors">
                                                <span class="flex-1">{{ $faq->title }}</span>
                                                <span class="shrink-0 w-6 h-6 rounded-full bg-gray-100 group-open:bg-orange-50 flex items-center justify-center transition-colors">
                                                    <svg class="w-4 h-4 text-gray-500 group-open:text-orange-600 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                    </svg>
                                                </span>
                                            </summary>

                                            <!-- FAQ Body - Contained width -->
                                            <div class="mt-3 pr-10 overflow-hidden">
                                                <div class="faq-content text-sm text-gray-600 leading-relaxed">
                                                    {!! $faq->body !!}
                                                </div>
                                            </div>
                                        </details>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- NO FAQs -->
                    <div class="flex flex-col items-center justify-center py-16 text-center">
                        <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z" />
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold text-gray-950 mb-2">No FAQs found</h3>
                        <p class="text-gray-500 text-sm max-w-sm">
                            @if(request('search'))
                                No results found for "{{ request('search') }}". Try a different search term.
                            @else
                                There are no frequently asked questions available for this department yet.
                            @endif
                        </p>
                        @if(request('search'))
                            <a href="{{ route('faq.index', ['department' => $currentDepartment->cms_department_slug]) }}"
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
