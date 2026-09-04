@props([
    'variant' => 'primary',
    'href' => null,
    'target' => null,
])

@php
    $baseClasses = 'inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium transition-colors duration-75 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-orange-600 shadow-sm';

    $variantClasses = match($variant) {
        'primary' => 'bg-orange-600 text-white hover:bg-orange-700',
        'ghost'   => 'bg-white text-gray-950 ring-1 ring-gray-950/10 hover:bg-gray-50',
        default   => 'bg-orange-600 text-white hover:bg-orange-700',
    };

    $classes = $baseClasses . ' ' . $variantClasses;
    $tag = $href ? 'a' : 'button';
@endphp

@if($href)
    <a href="{{ $href }}" @if($target) target="{{ $target }}" @endif {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['class' => $classes, 'type' => 'button']) }}>
        {{ $slot }}
    </button>
@endif
