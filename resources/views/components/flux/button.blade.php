@props([
    'type' => 'button',
    'variant' => null,
])

@php
$baseClasses = 'inline-flex items-center gap-2 rounded-lg font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors';
$variantClasses = match($variant) {
    'primary' => 'bg-transparent hover:bg-white/10 border border-white/20',
    default => 'bg-transparent'
};
@endphp

<button
    type="{{ $type }}"
    {{ $attributes->merge([
        'class' => $baseClasses . ' ' . $variantClasses
    ]) }}
>
    {{ $slot }}
</button>
