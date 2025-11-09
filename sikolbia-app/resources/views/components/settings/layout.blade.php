<div class="flex items-start max-md:flex-col">
    <div class="me-10 w-full pb-4 md:w-[220px]">
        <flux:navlist>
            <flux:navlist.item :href="route('settings.profile')" wire:navigate class="group">
                <span class="nav-link-text {{ request()->routeIs('settings.profile') ? 'text-neutral-900 dark:!text-white font-medium' : 'text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200' }}">{{ __('Profile') }}</span>
            </flux:navlist.item>
            <flux:navlist.item :href="route('settings.password')" wire:navigate class="group">
                <span class="nav-link-text {{ request()->routeIs('settings.password') ? 'text-neutral-900 dark:!text-white font-medium' : 'text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200' }}">{{ __('Password') }}</span>
            </flux:navlist.item>
            <flux:navlist.item :href="route('settings.appearance')" wire:navigate class="group">
                <span class="nav-link-text {{ request()->routeIs('settings.appearance') ? 'text-neutral-900 dark:!text-white font-medium' : 'text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200' }}">{{ __('Appearance') }}</span>
            </flux:navlist.item>
        </flux:navlist>
    </div>

    <flux:separator class="md:hidden" />

    <div class="flex-1 self-stretch max-md:pt-6">
        <flux:heading>{{ $heading ?? '' }}</flux:heading>
        <flux:subheading>{{ $subheading ?? '' }}</flux:subheading>

        <div class="mt-5 w-full max-w-lg">
            {{ $slot }}
        </div>
    </div>
</div>
