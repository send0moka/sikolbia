<?php

namespace App\Livewire\Admin\DaftarAlamat;

use App\Models\DaftarAlamat;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class DashboardDaftarAlamat extends Component
{
    public $totalAlamat;
    public $totalAktif;
    public $totalWithCoordinates;
    public $totalProvinsi;
    public $recentAlamat;
    public $statusStats;
    public $kategoriStats;
    public $wilayahStats;

    public function mount()
    {
        $this->loadStats();
    }

    public function loadStats()
    {
        $this->totalAlamat = DaftarAlamat::count();
        $this->totalAktif = DaftarAlamat::where('status', 'Aktif')->count();
        $this->totalWithCoordinates = DaftarAlamat::withCoordinates()->count();
        $this->totalProvinsi = DaftarAlamat::distinct('provinsi')->count();
        
        $this->recentAlamat = DaftarAlamat::whereNotNull('created_at')
            ->latest()
            ->take(5)
            ->get();

        $this->statusStats = DaftarAlamat::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status')
            ->toArray();

        $this->kategoriStats = DaftarAlamat::select('provinsi', DB::raw('count(*) as total'))
            ->whereNotNull('provinsi')
            ->groupBy('provinsi')
            ->orderByDesc('total')
            ->take(5)
            ->get()
            ->pluck('total', 'provinsi')
            ->toArray();

        $this->wilayahStats = DaftarAlamat::select('kabupaten_kota', DB::raw('count(*) as total'))
            ->groupBy('kabupaten_kota')
            ->orderByDesc('total')
            ->take(10)
            ->get()
            ->pluck('total', 'kabupaten_kota')
            ->toArray();
    }

    public function render()
    {
        return view('livewire.admin.daftar-alamat.dashboard-daftar-alamat');
    }
}
