@props([
    'label' => null,
    'name' => null,
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'disabled' => false,
    'error' => null,
    'help' => null,
    'rows' => 4
])

@php
    $id = $name ?? $attributes->get('id', 'textarea_' . uniqid());
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
        <textarea 
            id="{{ $id }}"
            name="{{ $name }}"
            rows="{{ $rows }}"
            placeholder="{{ $placeholder }}"
            @if($required) required @endif
            @if($disabled) disabled @endif
            {{ $attributes->merge([
                'class' => 'block w-full transition duration-200 ease-in-out pl-3 pr-3 py-2 border rounded-md shadow-sm ' .
                          'placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-opacity-50 resize-y ' .
                          'dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-500 dark:text-white ' .
                          ($disabled ? 'bg-gray-50 dark:bg-gray-800 cursor-not-allowed ' : '') .
                          $errorClass
            ]) }}
        >{{ old($name, $value) }}</textarea>

        @if($error)
        <div class="absolute top-2 right-2 pointer-events-none">
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