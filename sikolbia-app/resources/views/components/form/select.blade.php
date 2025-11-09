@props([
    'label' => null,
    'name' => null,
    'value' => '',
    'placeholder' => 'Choose an option...',
    'required' => false,
    'disabled' => false,
    'error' => null,
    'help' => null,
    'options' => [],
    'icon' => null,
    'multiple' => false
])

@php
    $id = $name ?? $attributes->get('id', 'select_' . uniqid());
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
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
            <i class="fas fa-{{ $icon }} text-gray-400"></i>
        </div>
        @endif

        <select 
            id="{{ $id }}"
            name="{{ $name }}"
            @if($required) required @endif
            @if($disabled) disabled @endif
            @if($multiple) multiple @endif
            {{ $attributes->merge([
                'class' => 'block w-full transition duration-200 ease-in-out ' .
                          ($icon ? 'pl-10 ' : 'pl-3 ') .
                          'pr-10 py-2 border rounded-md shadow-sm ' .
                          'focus:outline-none focus:ring-2 focus:ring-opacity-50 ' .
                          'dark:bg-gray-700 dark:border-gray-600 dark:text-white ' .
                          ($disabled ? 'bg-gray-50 dark:bg-gray-800 cursor-not-allowed ' : 'bg-white cursor-pointer ') .
                          $errorClass
            ]) }}
        >
            @if(!$multiple && $placeholder)
            <option value="">{{ $placeholder }}</option>
            @endif

            @if(is_array($options) || is_object($options))
                @foreach($options as $optionValue => $optionLabel)
                <option value="{{ $optionValue }}" {{ old($name, $value) == $optionValue ? 'selected' : '' }}>
                    {{ $optionLabel }}
                </option>
                @endforeach
            @else
                {{ $slot }}
            @endif
        </select>

        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
            @if($error)
            <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
            @endif
            <i class="fas fa-chevron-down text-gray-400 text-sm"></i>
        </div>
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