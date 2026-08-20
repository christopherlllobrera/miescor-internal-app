@foreach($posts as $post)
    <div class="bg-white border border-[#E5E7EB] rounded-lg shadow-sm ">
        <a href="{!! route('posts.show', $post->slug) !!}" rel="noreferrer">
            <img src="{{ $post->getThumbnailUrl() }}" class="rounded-t-lg w-full">
        </a>
        <div class="p-5">
            <a href="{!! route('posts.show', $post->slug) !!}" rel="noreferrer">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-[#111827]">{{ $post->title }}</h5>
            </a>
            <p class="mb-3 font-normal text-[#6B7280]">
                {!! $post->getExcerpt() !!}
            </p>
            <a href="{{ route('posts.show', $post->slug) }}" rel="noreferrer"
                class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-orange-600 rounded-lg hover:bg-orange-800 focus:ring-4 focus:outline-none focus:ring-orange-300 ">
                Read more
                <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M1 5h12m0 0L9 1m4 4L9 9" />
                </svg>
            </a>
        </div>
    </div>
@endforeach
