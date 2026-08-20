<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/miescor/favicon.ico') }}">
    <title>News Page</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="min-h-screen flex flex-col max-w-screen-4xl mx-auto w-full">
        @include('employee-portal.navigation')
        <main class="grow pt-20 md:pt-24">
            <section class="bg-white">
                <div class="py-8 px-8 mx-auto max-w-screen-2xl sm:py-16 lg:px-6">
                    <div class="mx-auto max-w-screen-sm text-center lg:mb-16 mb-8">
                        <h2
                            class="text-3xl sm:text-3xl md:text-4xl mt-8 lg:text-5xl font-bold mb-4 sm:mb-6 tracking-tight text-[#111827]">
                            Latest News
                        </h2>
                        <p class="font-normal text-[#6B7280] sm:text-xl">
                            Stay informed with the latest stories, announcements, and developments from MIESCOR. Your
                            source for
                            company news and industry insights.
                        </p>
                    </div>

                    <div class="space-y-8 md:grid md:grid-cols-2 lg:grid-cols-3 md:gap-12 md:space-y-0">
                        @foreach ($posts as $post)
                            <div class="bg-white border border-[#E5E7EB] rounded-lg shadow-sm ">
                                <a href="{!! route('posts.show', $post->slug) !!}" rel="noreferrer">
                                    <img src="{{ $post->getThumbnailUrl() }}" class="rounded-t-lg w-full">
                                </a>
                                <div class="p-5">
                                    <a href="{!! route('posts.show', $post->slug) !!}" rel="noreferrer">
                                        <h5 class="mb-2 text-2xl font-bold tracking-tight text-[#111827]">
                                            {{ $post->title }}</h5>
                                    </a>
                                    <p class="mb-3 font-normal text-[#6B7280]">
                                        {!! $post->getExcerpt() !!}
                                    </p>
                                    <a href="{{ route('posts.show', $post->slug) }}" rel="noreferrer"
                                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-orange-600 rounded-lg hover:bg-orange-800 focus:ring-4 focus:outline-none focus:ring-orange-300 ">
                                        Read more
                                        <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        </main>
        @include('employee-portal.footer')
    </div>
</body>

</html>
