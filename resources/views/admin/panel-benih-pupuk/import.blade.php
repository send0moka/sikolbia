@extends('layouts.benih-pupuk')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Impor Data Benih & Pupuk') }}
        </h2>
    </div>
@endsection

@section('content')
<div class="overflow-hidden">
    <div class="p-6 lg:p-8">
        <livewire:admin.benih-pupuk.import-benih-pupuk />
    </div>
</div>
@endsection
