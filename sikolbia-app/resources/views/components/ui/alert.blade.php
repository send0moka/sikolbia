@props([
    'type' => 'info', // success, error, warning, info
    'dismissible' => false,
    'icon' => true,
    'title' => null
])

@php
    $baseClasses = 'p-4 rounded-md border';
    
    $typeClasses = [
        'success' => 'bg-green-50 border-green-200 text-green-800 dark:bg-green-900/20 dark:border-green-700 dark:text-green-200',
        'error' => 'bg-red-50 border-red-200 text-red-800 dark:bg-red-900/20 dark:border-red-700 dark:text-red-200',
        'warning' => 'bg-yellow-50 border-yellow-200 text-yellow-800 dark:bg-yellow-900/20 dark:border-yellow-700 dark:text-yellow-200',
        'info' => 'bg-blue-50 border-blue-200 text-blue-800 dark:bg-blue-900/20 dark:border-blue-700 dark:text-blue-200'
    ];
    
    $iconClasses = [
        'success' => 'fas fa-check-circle text-green-400',
        'error' => 'fas fa-exclamation-circle text-red-400',
        'warning' => 'fas fa-exclamation-triangle text-yellow-400',
        'info' => 'fas fa-info-circle text-blue-400'
    ];
    
    $classes = $baseClasses . ' ' . ($typeClasses[$type] ?? $typeClasses['info']);
@endphp

<div {{ $attributes->merge(['class' => $classes]) }} 
     @if($dismissible) x-data="{ show: true }" x-show="show" x-transition @endif>
    <div class="flex {{ $dismissible ? 'justify-between' : '' }}">
        <div class="flex">
            @if($icon)
            <div class="flex-shrink-0">
                <i class="{{ $iconClasses[$type] ?? $iconClasses['info'] }}"></i>
            </div>
            @endif
            
            <div class="{{ $icon ? 'ml-3' : '' }}">
                @if($title)
                <h3 class="text-sm font-medium">{{ $title }}</h3>
                @endif
                
                <div class="{{ $title ? 'mt-2 text-sm' : 'text-sm' }}">
                    {{ $slot }}
                </div>
            </div>
        </div>
        
        @if($dismissible)
        <div class="flex-shrink-0 ml-4">
            <button @click="show = false" 
                    class="rounded-md inline-flex text-current hover:opacity-75 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-current">
                <span class="sr-only">Dismiss</span>
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>
        @endif
    </div>
</div>