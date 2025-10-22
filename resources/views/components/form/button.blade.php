@props([
    'variant' => 'primary', // primary, secondary, success, danger, warning, info
    'size' => 'md', // sm, md, lg, xl
    'type' => 'button',
    'loading' => false,
    'disabled' => false,
    'icon' => null,
    'iconPosition' => 'left', // left, right
    'href' => null,
    'target' => null
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-medium rounded-md transition duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';
    
    $variantClasses = [
        'primary' => 'bg-blue-600 hover:bg-blue-700 text-white focus:ring-blue-500 border border-transparent',
        'secondary' => 'bg-gray-200 hover:bg-gray-300 text-gray-900 focus:ring-gray-500 border border-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-white dark:border-gray-600',
        'success' => 'bg-green-600 hover:bg-green-700 text-white focus:ring-green-500 border border-transparent',
        'danger' => 'bg-red-600 hover:bg-red-700 text-white focus:ring-red-500 border border-transparent',
        'warning' => 'bg-yellow-600 hover:bg-yellow-700 text-white focus:ring-yellow-500 border border-transparent',
        'info' => 'bg-indigo-600 hover:bg-indigo-700 text-white focus:ring-indigo-500 border border-transparent',
        'outline-primary' => 'border border-blue-600 text-blue-600 hover:bg-blue-50 focus:ring-blue-500 dark:hover:bg-blue-900/20',
        'outline-secondary' => 'border border-gray-300 text-gray-700 hover:bg-gray-50 focus:ring-gray-500 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-800',
        'ghost' => 'text-gray-700 hover:bg-gray-100 focus:ring-gray-500 dark:text-gray-300 dark:hover:bg-gray-800',
    ];
    
    $sizeClasses = [
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-6 py-3 text-base',
        'xl' => 'px-8 py-4 text-lg'
    ];
    
    $iconSizeClasses = [
        'sm' => 'text-xs',
        'md' => 'text-sm',
        'lg' => 'text-base',
        'xl' => 'text-lg'
    ];
    
    $classes = $baseClasses . ' ' . ($variantClasses[$variant] ?? $variantClasses['primary']) . ' ' . ($sizeClasses[$size] ?? $sizeClasses['md']);
    $iconSize = $iconSizeClasses[$size] ?? $iconSizeClasses['md'];
@endphp

@if($href)
    <a href="{{ $href }}" 
       @if($target) target="{{ $target }}" @endif
       {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon && $iconPosition === 'left')
            <i class="fas fa-{{ $icon }} {{ $iconSize }} {{ $slot->isEmpty() ? '' : 'mr-2' }}"></i>
        @endif
        
        {{ $slot }}
        
        @if($icon && $iconPosition === 'right')
            <i class="fas fa-{{ $icon }} {{ $iconSize }} {{ $slot->isEmpty() ? '' : 'ml-2' }}"></i>
        @endif
    </a>
@else
    <button type="{{ $type }}" 
            @if($disabled || $loading) disabled @endif
            {{ $attributes->merge(['class' => $classes]) }}>
        
        @if($loading)
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        @elseif($icon && $iconPosition === 'left')
            <i class="fas fa-{{ $icon }} {{ $iconSize }} {{ $slot->isEmpty() ? '' : 'mr-2' }}"></i>
        @endif
        
        {{ $loading ? 'Loading...' : $slot }}
        
        @if(!$loading && $icon && $iconPosition === 'right')
            <i class="fas fa-{{ $icon }} {{ $iconSize }} {{ $slot->isEmpty() ? '' : 'ml-2' }}"></i>
        @endif
    </button>
@endif