<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Kelompok;
use App\Models\Komoditi;
use App\Models\TbKelompokbps;
use App\Models\TbKomoditibps;
use App\Models\TransaksiSusenas;
use App\Models\TransaksiNbm;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    public function render()
    {
        $data = [];

        // Data untuk admin
        if (auth()->user()->hasRole(['superadmin', 'admin'])) {
            $data = [
                'totalUsers' => User::count(),
                'totalRoles' => Role::count(),
                'totalKelompok' => Kelompok::count(),
                'totalKomoditi' => Komoditi::count(),
                'totalTransaksiNbm' => TransaksiNbm::count(),
                'totalKelompokbps' => TbKelompokbps::count(),
                'totalKomoditibps' => TbKomoditibps::count(),
                'totalSusenas' => TransaksiSusenas::count(),
                'recentUsers' => User::latest()->take(5)->get(),
                'recentKelompok' => Kelompok::latest()->take(5)->get(),
                'recentKomoditi' => Komoditi::with('kelompok')->latest()->take(5)->get(),
                'recentTransaksiNbm' => TransaksiNbm::with('komoditi')->latest()->take(5)->get(),
                'recentKelompokbps' => TbKelompokbps::latest()->take(5)->get(),
                'recentKomoditibps' => TbKomoditibps::with('kelompokbps')->latest()->take(5)->get(),
                'recentSusenas' => TransaksiSusenas::with(['kelompokbps', 'komoditibps'])->latest()->take(5)->get(),
            ];
        }

        return view('livewire.dashboard', $data);
    }
}
