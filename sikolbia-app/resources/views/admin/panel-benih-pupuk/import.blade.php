@extends('layouts.benih-pupuk')

@section('header')
    <!-- Header removed - now using sticky header in Livewire component -->
@endsection

@section('content')
<div class="overflow-hidden">
    <div class="p-6 lg:p-8">
        <livewire:admin.benih-pupuk.import-benih-pupuk />
    </div>
</div>
@endsection
