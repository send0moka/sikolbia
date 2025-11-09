@props([
    'label' => null,
    'name' => null,
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'type' => 'text',
    'disabled' => false,
    'error' => null,
    'help' => null,
    'icon' => null,
    'step' => null,
    'min' => null,
    'max' => null
])

@php
    $id = $name ?? $attributes->get('id', 'input_' . uniqid());
    $errorClass = $error ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : 'border-gray-300 focus:border-blue-500 focus:ring-blue-500';
@endphp

<div class="form-group">
    @if($label)
    <label for="{{ $id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
        {{ $label }}
        @if($required)
        <span class="text-red-500 ml-1">*</span>
        @endif
    </label>
    @endif

    <div class="relative rounded-md shadow-sm">
        @if($icon)
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <i class="fas fa-{{ $icon }} text-gray-400"></i>
        </div>
        @endif

        <input 
            type="{{ $type }}"
            id="{{ $id }}"
            name="{{ $name }}"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            @if($required) required @endif
            @if($disabled) disabled @endif
            @if($step) step="{{ $step }}" @endif
            @if($min !== null) min="{{ $min }}" @endif
            @if($max !== null) max="{{ $max }}" @endif
            {{ $attributes->merge([
                'class' => 'block w-full transition duration-200 ease-in-out ' .
                          ($icon ? 'pl-10 ' : 'pl-3 ') .
                          'pr-3 py-2 border rounded-md shadow-sm placeholder-gray-400 ' .
                          'focus:outline-none focus:ring-2 focus:ring-opacity-50 ' .
                          'dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-500 dark:text-white ' .
                          ($disabled ? 'bg-gray-50 dark:bg-gray-800 cursor-not-allowed ' : '') .
                          $errorClass
            ]) }}
        >

        @if($error)
        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
            <i class="fas fa-exclamation-circle text-red-500"></i>
        </div>
        @endif
    </div>

    @if($error)
    <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
        <i class="fas fa-exclamation-triangle mr-1 text-xs"></i>
        {{ $error }}
    </p>
    @endif

    @if($help && !$error)
    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
        {{ $help }}
    </p>
    @endif
</div>