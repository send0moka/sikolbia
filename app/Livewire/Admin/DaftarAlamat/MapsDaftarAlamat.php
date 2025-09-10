<?php

namespace App\Livewire\Admin\DaftarAlamat;

use App\Models\DaftarAlamat;
use Livewire\Component;
use Livewire\Attributes\Url;

class MapsDaftarAlamat extends Component
{
    #[Url]
    public $statusFilter = '';
    
    #[Url]
    public $provinsiFilter = '';
    
    #[Url]
    public $kabupatenKotaFilter = '';

    public $selectedAlamat = null;
    public $showInfoModal = false;

    public function showInfo($id)
    {
        try {
            $this->selectedAlamat = DaftarAlamat::findOrFail($id);
            $this->showInfoModal = true;
        } catch (\Exception $e) {
            session()->flash('error', 'Data alamat tidak ditemukan.');
            $this->showInfoModal = false;
            $this->selectedAlamat = null;
        }
    }

    public function closeInfoModal()
    {
        $this->showInfoModal = false;
        $this->selectedAlamat = null;
    }

    public function resetFilters()
    {
        $this->reset(['statusFilter', 'provinsiFilter', 'kabupatenKotaFilter']);
        $this->updateMap();
    }

    public function updatedStatusFilter()
    {
        $this->updateMap();
    }

    public function updatedProvinsiFilter()
    {
        $this->updateMap();
    }

    public function updatedKabupatenKotaFilter()
    {
        $this->updateMap();
    }

    private function updateMap()
    {
        $query = DaftarAlamat::withCoordinates();

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->provinsiFilter) {
            $query->where('provinsi', $this->provinsiFilter);
        }

        if ($this->kabupatenKotaFilter) {
            $query->where('kabupaten_kota', $this->kabupatenKotaFilter);
        }

        $alamats = $query->get();

        $mapData = $alamats->map(function ($alamat) {
            return [
                'id' => $alamat->id,
                'lat' => (float) $alamat->latitude,
                'lng' => (float) $alamat->longitude,
                'title' => $alamat->nama_dinas,
                'provinsi' => $alamat->provinsi,
                'kabupaten_kota' => $alamat->kabupaten_kota,
                'alamat' => $alamat->alamat,
                'status' => $alamat->status,
                'telp' => $alamat->telp,
                'email' => $alamat->email,
                'gambar' => $alamat->gambar ? asset('storage/' . $alamat->gambar) : null,
            ];
        });

        $this->dispatch('mapUpdated', $mapData);
    }

    public function render()
    {
        $query = DaftarAlamat::withCoordinates();

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->provinsiFilter) {
            $query->where('provinsi', $this->provinsiFilter);
        }

        if ($this->kabupatenKotaFilter) {
            $query->where('kabupaten_kota', $this->kabupatenKotaFilter);
        }

        $alamats = $query->get();

        $statusOptions = DaftarAlamat::getStatusOptions();
        
        $provinsiOptions = DaftarAlamat::distinct('provinsi')
                                   ->orderBy('provinsi')
                                   ->pluck('provinsi')
                                   ->filter()
                                   ->toArray();
        
        $kabupatenKotaOptions = DaftarAlamat::when($this->provinsiFilter, function($query) {
                                        return $query->where('provinsi', $this->provinsiFilter);
                                    })
                                    ->distinct('kabupaten_kota')
                                    ->orderBy('kabupaten_kota')
                                    ->pluck('kabupaten_kota')
                                    ->filter()
                                    ->toArray();

        // Prepare map data
        $mapData = $alamats->map(function ($alamat) {
            return [
                'id' => $alamat->id,
                'lat' => (float) $alamat->latitude,
                'lng' => (float) $alamat->longitude,
                'title' => $alamat->nama_dinas,
                'provinsi' => $alamat->provinsi,
                'kabupaten_kota' => $alamat->kabupaten_kota,
                'alamat' => $alamat->alamat,
                'status' => $alamat->status,
                'telp' => $alamat->telp,
                'email' => $alamat->email,
                'gambar' => $alamat->gambar ? asset('storage/' . $alamat->gambar) : null,
            ];
        });

        return view('livewire.admin.daftar-alamat.maps-daftar-alamat', compact(
            'alamats', 'statusOptions', 'provinsiOptions', 'kabupatenKotaOptions', 'mapData'
        ));
    }
}
