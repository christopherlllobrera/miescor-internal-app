<div class="w-full relative h-screen">
    <div class="swiper carousel-swiper h-full">

        <div class="swiper-wrapper">
            @foreach($carousels as $carousel)
                <div class="swiper-slide">
                    <div class="relative w-full h-full">
                        <img src="{{ $carousel->image_url }}"
                            class="w-full h-full object-cover object-center"
                            alt="{{ $carousel->title ?? 'Carousel image' }}">
                        <div
                            class="absolute inset-0 bg-black/30 flex flex-col items-center justify-center px-6 sm:px-10 text-center">
                            <div class="max-w-5xl">
                                <h1 class="text-5xl md:text-6xl lg:text-8xl font-bold text-white">
                                    {{ $carousel->title }}
                                </h1>

                                @if(!empty($carousel->subtitle))
                                    <p class="mt-4 text-xl md:text-2xl lg:text-3xl text-white">
                                        {{ $carousel->subtitle }}
                                    </p>
                                @endif

                                @if(!empty($carousel->button_text) && !empty($carousel->button_link))
                                    <a href="{{ $carousel->button_link }}" target="_blank"
                                        class="mt-6 inline-block bg-orange-600 hover:bg-orange-500 text-white px-6 py-3 rounded-xl">
                                        {{ $carousel->button_text }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="swiper-pagination"></div>

    </div>
</div>
