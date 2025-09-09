@extends('layouts.lahan')

@section('content')
    <div>
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">Dashboard Lahan</h1>
            <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                Ringkasan statistik dan data terkini lahan pertanian
            </p>
        </div>
        
        <!-- Livewire Dashboard Component -->
        @livewire('admin.lahan.dashboard')
    </div>
@endsection