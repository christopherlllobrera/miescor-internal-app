@props([
    'padding' => 'p-5',
    'hover' => false,
])

<div {{ $attributes->merge([
    'class' => 'bg-white rounded-xl shadow-sm ring-1 ring-gray-950/5 ' . $padding . ($hover ? ' hover:shadow-md transition-shadow' : ''),
]) }}>
    {{ $slot }}
</div>
