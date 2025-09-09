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
    public $kategoriFilter = '';
    
    #[Url]
    public $wilayahFilter = '';

    public $selectedAlamat = null;
    public $showInfoModal = false;

    public function showInfo($id)
    {
        $this->selectedAlamat = DaftarAlamat::findOrFail($id);
        $this->showInfoModal = true;
    }

    public function closeInfoModal()
    {
        $this->showInfoModal = false;
        $this->selectedAlamat = null;
    }

    public function resetFilters()
    {
        $this->reset(['statusFilter', 'kategoriFilter', 'wilayahFilter']);
        $this->updateMap();
    }

    public function updatedStatusFilter()
    {
        $this->updateMap();
    }

    public function updatedKategoriFilter()
    {
        $this->updateMap();
    }

    public function updatedWilayahFilter()
    {
        $this->updateMap();
    }

    private function updateMap()
    {
        $query = DaftarAlamat::withCoordinates();

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->kategoriFilter) {
            $query->where('kategori', $this->kategoriFilter);
        }

        if ($this->wilayahFilter) {
            $query->where('wilayah', 'like', '%' . $this->wilayahFilter . '%');
        }

        $alamats = $query->get();

        $mapData = $alamats->map(function ($alamat) {
            return [
                'id' => $alamat->id,
                'lat' => (float) $alamat->latitude,
                'lng' => (float) $alamat->longitude,
                'title' => $alamat->nama_dinas,
                'wilayah' => $alamat->wilayah,
                'alamat' => $alamat->alamat,
                'status' => $alamat->status,
                'kategori' => $alamat->kategori,
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

        if ($this->kategoriFilter) {
            $query->where('kategori', $this->kategoriFilter);
        }

        if ($this->wilayahFilter) {
            $query->where('wilayah', 'like', '%' . $this->wilayahFilter . '%');
        }

        $alamats = $query->get();

        $statusOptions = DaftarAlamat::getStatusOptions();
        $kategoriOptions = DaftarAlamat::getKategoriOptions();
        
        $wilayahOptions = DaftarAlamat::distinct('wilayah')
                                   ->orderBy('wilayah')
                                   ->pluck('wilayah')
                                   ->toArray();

        // Prepare map data
        $mapData = $alamats->map(function ($alamat) {
            return [
                'id' => $alamat->id,
                'lat' => (float) $alamat->latitude,
                'lng' => (float) $alamat->longitude,
                'title' => $alamat->nama_dinas,
                'wilayah' => $alamat->wilayah,
                'alamat' => $alamat->alamat,
                'status' => $alamat->status,
                'kategori' => $alamat->kategori,
                'telp' => $alamat->telp,
                'email' => $alamat->email,
                'gambar' => $alamat->gambar ? asset('storage/' . $alamat->gambar) : null,
            ];
        });

        return view('livewire.admin.daftar-alamat.maps-daftar-alamat', compact(
            'alamats', 'statusOptions', 'kategoriOptions', 'wilayahOptions', 'mapData'
        ));
    }
}
