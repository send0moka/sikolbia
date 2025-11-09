@props([
    'title' => null,
    'subtitle' => null,
    'icon' => null,
    'iconColor' => 'blue',
    'hover' => false,
    'padding' => 'default', // none, sm, default, lg
    'shadow' => 'default' // none, sm, default, lg, xl
])

@php
    $baseClasses = 'bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg';
    
    $shadowClasses = [
        'none' => '',
        'sm' => 'shadow-sm',
        'default' => 'shadow-md',
        'lg' => 'shadow-lg',
        'xl' => 'shadow-xl'
    ];
    
    $paddingClasses = [
        'none' => '',
        'sm' => 'p-4',
        'default' => 'p-6',
        'lg' => 'p-8'
    ];
    
    $hoverClasses = $hover ? 'transition-all duration-200 hover:shadow-lg hover:-translate-y-1 cursor-pointer' : '';
    
    $iconColors = [
        'blue' => 'text-blue-600 bg-blue-100 dark:bg-blue-900',
        'green' => 'text-green-600 bg-green-100 dark:bg-green-900',
        'red' => 'text-red-600 bg-red-100 dark:bg-red-900',
        'yellow' => 'text-yellow-600 bg-yellow-100 dark:bg-yellow-900',
        'purple' => 'text-purple-600 bg-purple-100 dark:bg-purple-900',
        'indigo' => 'text-indigo-600 bg-indigo-100 dark:bg-indigo-900',
        'pink' => 'text-pink-600 bg-pink-100 dark:bg-pink-900',
        'gray' => 'text-gray-600 bg-gray-100 dark:bg-gray-700'
    ];
    
    $classes = $baseClasses . ' ' . 
               ($shadowClasses[$shadow] ?? $shadowClasses['default']) . ' ' . 
               ($paddingClasses[$padding] ?? $paddingClasses['default']) . ' ' . 
               $hoverClasses;
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    @if($title || $subtitle || $icon)
    <div class="flex items-start {{ $padding === 'none' ? 'p-6 pb-4' : 'mb-4' }}">
        @if($icon)
        <div class="flex-shrink-0 mr-4">
            <div class="w-10 h-10 rounded-lg flex items-center justify-center {{ $iconColors[$iconColor] ?? $iconColors['blue'] }}">
                <i class="fas fa-{{ $icon }} text-lg"></i>
            </div>
        </div>
        @endif
        
        @if($title || $subtitle)
        <div class="flex-1 min-w-0">
            @if($title)
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white leading-6">
                {{ $title }}
            </h3>
            @endif
            
            @if($subtitle)
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ $subtitle }}
            </p>
            @endif
        </div>
        @endif
    </div>
    @endif
    
    <div class="{{ ($title || $subtitle || $icon) && $padding !== 'none' ? '' : ($padding === 'none' ? 'p-6 pt-0' : '') }}">
        {{ $slot }}
    </div>
</div>