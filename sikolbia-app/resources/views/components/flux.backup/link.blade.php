@props(['href' => null, 'variant' => null])

@php
$baseClasses = 'inline-flex items-center gap-2';
$variantClasses = match($variant) {
    'ghost' => 'text-neutral-600 hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-white',
    default => ''
};
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $baseClasses . ' ' . $variantClasses]) }}>
        {{ $slot }}
    </a>
@else
    <button type="button" {{ $attributes->merge(['class' => $baseClasses . ' ' . $variantClasses]) }}>
        {{ $slot }}
    </button>
@endif
