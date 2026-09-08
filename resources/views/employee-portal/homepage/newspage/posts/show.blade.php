<x-portal.layout title="{!! $post->title !!}">
    <x-portal.navigation />
    <section class="relative pt-20 pb-20 bg-white ">
        <div
            class="w-full max-w-screen-2xl mt-3 shadow-xl rounded-xl px-4 md:px-8 mx-auto">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                    <li class="inline-flex items-center">
                        <a href="/"
                            class="inline-flex items-center text-sm font-medium text-[#111827] hover:text-orange-600 ">
                            <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                            </svg>
                            Home
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="rtl:rotate-180 w-3 h-3 text-[#6B7280] mx-1" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 9 4-4-4-4" />
                            </svg>
                            <a href="/blog"
                                class="ms-1 text-sm font-medium text-[#111827] hover:text-orange-600 md:ms-2 ">News</a>
                        </div>
                    </li>

                </ol>
            </nav>
            <div class="relative w-full mb-10 mt-6 rounded-2xl overflow-hidden bg-gray-100 shadow-sm h-64 sm:h-80 md:h-[400px] lg:h-[500px] xl:h-[600px]">
                <img src="{!! $post->getThumbnailUrl() !!}" alt="{!! $post->title !!}" class="w-full h-full object-cover">
            </div>
            <h1 class="text-[#111827] font-manrope font-semibold text-4xl min-[500px]:text-5xl leading-tight mb-8">
                {!! $post->title !!}
            </h1>
            <div class="flex items-center justify-between pb-8">
                <div class="data">
                    <p class="font-medium text-xl leading-8 text-[#111827] mb-1">{!! $post->published_at->diffForHumans() !!}
                    </p>
                    <p class="font-normal text-lg leading-7 text-[#111827]">{!! $post->author->name !!}</p>
                    <article
                        class="prose prose-lg max-w-none prose-headings:text-[#111827] prose-p:text-[#374151] prose-a:text-[#ea580c] prose-a:no-underline hover:prose-a:underline prose-strong:text-[#111827] prose-img:rounded-lg prose-img:shadow-md prose-ul:text-[#374151] prose-ol:text-[#374151] prose-blockquote:border-l-[#ea580c] prose-blockquote:text-[#6B7280]">
                        {!! $post->body !!}
                    </article>
                </div>
            </div>
        </div>
    </section>
    <x-portal.footer />
</x-portal.layout>
