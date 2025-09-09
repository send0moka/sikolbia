<?php

namespace App\Livewire\Admin\Lahan;

use App\Models\LahanData;
use App\Models\LahanTopik;
use App\Models\LahanVariabel;
use App\Models\LahanKlasifikasi;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Categories extends Component
{
    public $activeTab = 'topik';
    
    // Statistics
    public $topikStats = [];
    public $variabelStats = [];
    public $klasifikasiStats = [];
    
    public function mount()
    {
        $this->loadStatistics();
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    private function loadStatistics()
    {
        // Topik statistics
        $this->topikStats = LahanTopik::withCount('data')
            ->get()
            ->map(function($topik) {
                $avgNilai = $topik->data()->avg('nilai') ?? 0;
                $totalNilai = $topik->data()->sum('nilai') ?? 0;
                
                return [
                    'id' => $topik->id,
                    'deskripsi' => $topik->deskripsi,
                    'data_count' => $topik->data_count,
                    'avg_nilai' => round($avgNilai, 2),
                    'total_nilai' => $totalNilai
                ];
            })->toArray();

        // Variabel statistics
        $this->variabelStats = LahanVariabel::withCount('data')
            ->get()
            ->map(function($variabel) {
                $avgNilai = $variabel->data()->avg('nilai') ?? 0;
                $totalNilai = $variabel->data()->sum('nilai') ?? 0;
                
                return [
                    'id' => $variabel->id,
                    'deskripsi' => $variabel->deskripsi,
                    'satuan' => $variabel->satuan,
                    'data_count' => $variabel->data_count,
                    'avg_nilai' => round($avgNilai, 2),
                    'total_nilai' => $totalNilai
                ];
            })->toArray();

        // Klasifikasi statistics
        $this->klasifikasiStats = LahanKlasifikasi::withCount('data')
            ->get()
            ->map(function($klasifikasi) {
                $avgNilai = $klasifikasi->data()->avg('nilai') ?? 0;
                $totalNilai = $klasifikasi->data()->sum('nilai') ?? 0;
                
                return [
                    'id' => $klasifikasi->id,
                    'deskripsi' => $klasifikasi->deskripsi,
                    'data_count' => $klasifikasi->data_count,
                    'avg_nilai' => round($avgNilai, 2),
                    'total_nilai' => $totalNilai
                ];
            })->toArray();
    }

    public function render()
    {
        return view('livewire.admin.lahan.categories');
    }
}
