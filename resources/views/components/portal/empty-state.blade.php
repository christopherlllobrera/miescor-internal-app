@props([
    'heading',
    'description' => null,
])

<div class="text-center py-12">
    {{-- Icon circle --}}
    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
        {{ $icon }}
    </div>

    <h3 class="text-base font-semibold text-gray-950 mb-2">
        {{ $heading }}
    </h3>

    @if($description)
        <p class="text-gray-500 text-sm">
            {{ $description }}
        </p>
    @endif
</div>
