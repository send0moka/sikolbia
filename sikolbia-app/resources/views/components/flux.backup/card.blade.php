@props(['title' => null])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-neutral-800 overflow-hidden shadow-sm sm:rounded-lg']) }}>
    @if($title)
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg font-medium leading-6 text-neutral-900 dark:text-neutral-100">{{ $title }}</h3>
        </div>
    @endif
    <div class="px-4 py-5 sm:p-6">
        {{ $slot }}
    </div>
</div>
