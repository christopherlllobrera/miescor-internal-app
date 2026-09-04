@foreach($featuredPosts as $featuredPost)
<x-portal.card padding="p-0" class="flex flex-col overflow-hidden">
    <a href="{!! route('posts.show', $featuredPost->slug) !!}" rel="noreferrer">
        @if($featuredPost->image)
            <img src="{{ $featuredPost->getThumbnailUrl() }}" rel="noreferrer" class="rounded-t-xl h-80 w-full object-cover" alt="{{ $featuredPost->title }}">
        @else
            <img src="{!! URL('images/tower.jpg') !!}" rel="noreferrer" class="rounded-t-xl h-80 w-full object-cover" alt="Default Image">
        @endif
    </a>
    <div class="p-5 flex flex-col justify-between h-full">
        <div>
            <a href="{!! route('posts.show', $featuredPost->slug) !!}" rel="noreferrer">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-950">{{ $featuredPost->title }}</h5>
            </a>
            <p class="mb-3 font-normal text-gray-700">
                {!! Str::limit(strip_tags($featuredPost->excerpt ?? $featuredPost->body), 150) !!}
            </p>
        </div>
        <div>
            <x-portal.button :href="route('posts.show', $featuredPost->slug)" rel="noreferrer">
                Read more
                <svg class="rtl:rotate-180 w-3.5 h-3.5" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M1 5h12m0 0L9 1m4 4L9 9" />
                </svg>
            </x-portal.button>
        </div>
    </div>
</x-portal.card>
@endforeach
