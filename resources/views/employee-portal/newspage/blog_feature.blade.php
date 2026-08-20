@foreach($featuredPosts as $featuredPost)
<div class="bg-white border border-[#e5e7eb] rounded-lg shadow-sm flex flex-col">
    <a href="{!!  route('posts.show', $featuredPost->slug) !!}" rel="noreferrer">
        @if($featuredPost->image)
            <img src="{{ $featuredPost->getThumbnailUrl() }}" rel="noreferrer" class="rounded-t-lg h-80 w-full object-cover" alt="{{ $featuredPost->title }}">
        @else
            <img src="{!! URL('images/tower.jpg') !!}" rel="noreferrer" class="rounded-t-lg h-80 w-full object-cover" alt="Default Image">
        @endif
    </a>
    <div class="p-5 flex flex-col justify-between h-full">
        <div>
            <a href="{!! route('posts.show', $featuredPost->slug) !!}" rel="noreferrer">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-[#111827] ">{{ $featuredPost->title }}</h5>
            </a>
            <p class="mb-3 font-normal text-[#374151]">
                {!! Str::limit(strip_tags($featuredPost->excerpt ?? $featuredPost->body), 150) !!}
            </p>
        </div>
        <div>
            <a href="{!! route('posts.show', $featuredPost->slug) !!}" rel="noreferrer"
                class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-orange-600 rounded-lg hover:bg-orange-500 focus:ring-4 focus:outline-none focus:ring-orange-300 mt-auto">
                Read more
                <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M1 5h12m0 0L9 1m4 4L9 9" />
                </svg>
            </a>
        </div>
    </div>
</div>
@endforeach
