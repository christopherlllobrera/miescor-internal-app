@props([
    'title',
    'subtitle' => null,
    'centered' => true,
])

<div @class([
    'mb-8 sm:mb-12',
    'text-center' => $centered,
])>
    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold tracking-tight text-gray-950 mb-4 sm:mb-6">
        {{ $title }}
    </h2>
    @if($subtitle)
        <p @class([
            'text-gray-500 text-base font-normal sm:text-lg',
            'max-w-2xl mx-auto' => $centered,
        ])>
            {{ $subtitle }}
        </p>
    @endif
</div>
