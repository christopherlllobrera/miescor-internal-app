<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $faq->title }} - FAQ - I AM MIESCOR</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/miescor/favicon.ico') }}">
    <meta name="description" content="{{ Str::limit(strip_tags($faq->body), 160) }}">
    <meta name="keywords" content="MIESCOR, FAQ, {{ $currentDepartment->display_name }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 min-h-screen text-gray-950 antialiased">
    <header class="fixed w-full top-0 z-50 bg-white transition-transform duration-300 shadow-md">
        <nav class="px-4 md:px-6 py-3 relative">
            <div class="flex justify-between items-center mx-auto max-w-screen-4xl relative">
                <a href="/" rel="noreferrer" class="flex items-center z-10">
                    <img src="{!! URL('images/logo/miescor_light_mode.png') !!}" class="h-10 sm:h-12 lg:h-14" alt="Miescor Logo" />
                </a>
            </div>
        </nav>
    </header>

    <main class="pt-20 sm:pt-24 lg:pt-28">
        <!-- Breadcrumb -->
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 pt-4 sm:pt-6">
            <nav class="flex items-center gap-2 text-sm text-gray-500">
                <a href="{{ route('department.show', $currentDepartment->cms_department_slug) }}" class="hover:text-primary-600 transition-colors">
                    {{ $currentDepartment->display_name }}
                </a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <a href="{{ route('faq.index', ['department' => $currentDepartment->cms_department_slug]) }}" class="hover:text-primary-600 transition-colors">
                    FAQ
                </a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-gray-950 font-medium truncate">{{ $faq->title }}</span>
            </nav>
        </div>

        <!-- FAQ Content -->
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-8 sm:py-10 lg:py-12">
            <article>
                <h1 class="text-2xl sm:text-3xl font-semibold text-gray-950 mb-6">
                    {{ $faq->title }}
                </h1>

                <div class="faq-content text-gray-700 leading-relaxed">
                    {!! $faq->body !!}
                </div>
            </article>

            <!-- Related FAQs -->
            @if($relatedFaqs->count() > 0)
                <hr class="my-10 border-gray-200">

                <section>
                    <h2 class="text-lg font-semibold text-gray-950 mb-4">Related FAQs</h2>
                    <div class="space-y-3">
                        @foreach($relatedFaqs as $related)
                            <a href="{{ route('faq.show', ['department' => $currentDepartment->cms_department_slug, 'slug' => $related->slug]) }}"
                                class="block px-4 py-3 rounded-xl border border-gray-100 hover:border-primary-200 hover:bg-primary-50 transition-colors">
                                <span class="text-sm font-medium text-gray-700">{{ $related->title }}</span>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif

            <!-- Back link -->
            <div class="mt-10">
                <a href="{{ route('faq.index', ['department' => $currentDepartment->cms_department_slug]) }}"
                    class="inline-flex items-center gap-1 text-sm text-gray-600 hover:text-primary-600 font-medium transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                    Back to all FAQs
                </a>
            </div>
        </div>
    </main>

    <style>
        .faq-content {
            max-width: 100%;
            overflow-wrap: break-word;
            word-wrap: break-word;
            word-break: break-word;
        }

        .faq-content * {
            max-width: 100%;
            font-size: 0.9375rem;
            line-height: 1.75;
        }

        .faq-content p {
            margin-bottom: 1rem;
        }

        .faq-content p:last-child {
            margin-bottom: 0;
        }

        .faq-content ul,
        .faq-content ol {
            margin-bottom: 1rem;
            padding-left: 1.5rem;
        }

        .faq-content ul {
            list-style-type: disc;
        }

        .faq-content ol {
            list-style-type: decimal;
        }

        .faq-content li {
            margin-bottom: 0.375rem;
        }

        .faq-content h1,
        .faq-content h2,
        .faq-content h3,
        .faq-content h4 {
            font-size: 1.125rem;
            font-weight: 600;
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
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
            margin: 1rem 0;
        }

        .faq-content table {
            width: 100%;
            border-collapse: collapse;
            margin: 1rem 0;
            font-size: 0.875rem;
        }

        .faq-content th,
        .faq-content td {
            border: 1px solid var(--color-gray-200, #e5e7eb);
            padding: 0.5rem;
            text-align: left;
        }

        .faq-content a {
            color: var(--color-primary-600, #ea580c);
            text-decoration: underline;
        }

        .faq-content a:hover {
            color: var(--color-primary-500, #f97316);
        }

        .faq-content pre,
        .faq-content code {
            font-size: 0.8125rem;
            background-color: var(--color-gray-100, #f3f4f6);
            border-radius: 0.25rem;
            overflow-x: auto;
        }

        .faq-content pre {
            padding: 0.75rem;
            margin: 1rem 0;
        }

        .faq-content code {
            padding: 0.125rem 0.25rem;
        }

        .faq-content blockquote {
            border-left: 3px solid var(--color-primary-600, #ea580c);
            padding-left: 1rem;
            margin: 1rem 0;
            color: var(--color-gray-600, #4b5563);
            font-style: italic;
        }

        @media (prefers-color-scheme: dark) {
            .faq-content pre,
            .faq-content code {
                background-color: var(--color-gray-800, #1f2937);
            }
            .faq-content th,
            .faq-content td {
                border-color: var(--color-gray-700, #374151);
            }
            .faq-content blockquote {
                color: var(--color-gray-400, #9ca3af);
            }
        }
    </style>
</body>

</html>
