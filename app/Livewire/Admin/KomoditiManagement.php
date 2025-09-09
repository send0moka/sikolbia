<?php

namespace App\Livewire\Admin;

use App\Models\Komoditi;
use App\Models\Kelompok;
use App\Exports\KomoditiExport;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class KomoditiManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public array $perPageOptions = [5, 10, 25, 100];
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    
    public $kode_kelompok = '';
    public $kode_komoditi = '';
    public $nama = '';
    public $satuan_dasar = '';
    public $kalori_per_100g = '';
    public $protein_per_100g = '';
    public $lemak_per_100g = '';
    public $karbohidrat_per_100g = '';
    public $serat_per_100g = '';
    public $vitamin_c_per_100g = '';
    public $zat_besi_per_100g = '';
    public $kalsium_per_100g = '';
    public $musim_panen = '';
    public $asal_produksi = '';
    public $shelf_life_hari = '';
    public $harga_rata_per_kg = '';
    public $editingKomoditi = null;
    public $deletingKomoditi = null;
    public $exportFormat = 'xlsx';

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
    ];

    protected $casts = [
        'perPage' => 'integer',
    ];

    protected $rules = [
        'kode_kelompok' => 'required|exists:kelompok,kode',
        'kode_komoditi' => 'required|unique:komoditi,kode_komoditi',
        'nama' => 'required|min:3',
        'satuan_dasar' => 'required|string',
        'kalori_per_100g' => 'nullable|numeric',
        'protein_per_100g' => 'nullable|numeric',
        'lemak_per_100g' => 'nullable|numeric',
        'karbohidrat_per_100g' => 'nullable|numeric',
        'serat_per_100g' => 'nullable|numeric',
        'vitamin_c_per_100g' => 'nullable|numeric',
        'zat_besi_per_100g' => 'nullable|numeric',
        'kalsium_per_100g' => 'nullable|numeric',
        'musim_panen' => 'nullable|string',
        'asal_produksi' => 'nullable|string',
        'shelf_life_hari' => 'nullable|integer',
        'harga_rata_per_kg' => 'nullable|numeric',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedPerPage($value)
    {
        if (! in_array((int)$value, $this->perPageOptions, true)) {
            $this->perPage = 10; // fallback
        }
        $this->resetPage();
    }

    public function updatingPerPage($value)
    {
        // Reset pagination before value changes to avoid out-of-range page
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->resetForm();
    }

    public function openEditModal($komoditiId)
    {
    $this->editingKomoditi = Komoditi::findOrFail($komoditiId);
    $this->kode_kelompok = $this->editingKomoditi->kode_kelompok;
    $this->kode_komoditi = $this->editingKomoditi->kode_komoditi;
    $this->nama = $this->editingKomoditi->nama;
    $this->satuan_dasar = $this->editingKomoditi->satuan_dasar;
    $this->kalori_per_100g = $this->editingKomoditi->kalori_per_100g;
    $this->protein_per_100g = $this->editingKomoditi->protein_per_100g;
    $this->lemak_per_100g = $this->editingKomoditi->lemak_per_100g;
    $this->karbohidrat_per_100g = $this->editingKomoditi->karbohidrat_per_100g;
    $this->serat_per_100g = $this->editingKomoditi->serat_per_100g;
    $this->vitamin_c_per_100g = $this->editingKomoditi->vitamin_c_per_100g;
    $this->zat_besi_per_100g = $this->editingKomoditi->zat_besi_per_100g;
    $this->kalsium_per_100g = $this->editingKomoditi->kalsium_per_100g;
    $this->musim_panen = $this->editingKomoditi->musim_panen;
    $this->asal_produksi = $this->editingKomoditi->asal_produksi;
    $this->shelf_life_hari = $this->editingKomoditi->shelf_life_hari;
    $this->harga_rata_per_kg = $this->editingKomoditi->harga_rata_per_kg;
    $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->resetForm();
    }

    public function openDeleteModal($komoditiId)
    {
        $this->deletingKomoditi = Komoditi::findOrFail($komoditiId);
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->deletingKomoditi = null;
    }

    public function createKomoditi()
    {
        $this->validate();

        Komoditi::create([
            'kode_kelompok' => $this->kode_kelompok,
            'kode_komoditi' => $this->kode_komoditi,
            'nama' => $this->nama,
            'satuan_dasar' => $this->satuan_dasar,
            'kalori_per_100g' => $this->kalori_per_100g,
            'protein_per_100g' => $this->protein_per_100g,
            'lemak_per_100g' => $this->lemak_per_100g,
            'karbohidrat_per_100g' => $this->karbohidrat_per_100g,
            'serat_per_100g' => $this->serat_per_100g,
            'vitamin_c_per_100g' => $this->vitamin_c_per_100g,
            'zat_besi_per_100g' => $this->zat_besi_per_100g,
            'kalsium_per_100g' => $this->kalsium_per_100g,
            'musim_panen' => $this->musim_panen,
            'asal_produksi' => $this->asal_produksi,
            'shelf_life_hari' => $this->shelf_life_hari,
            'harga_rata_per_kg' => $this->harga_rata_per_kg,
        ]);

        session()->flash('message', 'Komoditi berhasil dibuat.');
        $this->closeCreateModal();
    }

    public function updateKomoditi()
    {
        $rules = [
            'kode_kelompok' => 'required|exists:kelompok,kode',
            'kode_komoditi' => 'required|unique:komoditi,kode_komoditi,' . $this->editingKomoditi->id,
            'nama' => 'required|min:3',
        ];

        $this->validate($rules);

        $this->editingKomoditi->update([
            'kode_kelompok' => $this->kode_kelompok,
            'kode_komoditi' => $this->kode_komoditi,
            'nama' => $this->nama,
            'satuan_dasar' => $this->satuan_dasar,
            'kalori_per_100g' => $this->kalori_per_100g,
            'protein_per_100g' => $this->protein_per_100g,
            'lemak_per_100g' => $this->lemak_per_100g,
            'karbohidrat_per_100g' => $this->karbohidrat_per_100g,
            'serat_per_100g' => $this->serat_per_100g,
            'vitamin_c_per_100g' => $this->vitamin_c_per_100g,
            'zat_besi_per_100g' => $this->zat_besi_per_100g,
            'kalsium_per_100g' => $this->kalsium_per_100g,
            'musim_panen' => $this->musim_panen,
            'asal_produksi' => $this->asal_produksi,
            'shelf_life_hari' => $this->shelf_life_hari,
            'harga_rata_per_kg' => $this->harga_rata_per_kg,
        ]);

        session()->flash('message', 'Komoditi berhasil diupdate.');
        $this->closeEditModal();
    }

    public function deleteKomoditi()
    {
        if ($this->deletingKomoditi) {
            $this->deletingKomoditi->delete();
            session()->flash('message', 'Komoditi berhasil dihapus.');
            $this->closeDeleteModal();
        }
    }

    private function resetForm()
    {
    $this->kode_kelompok = '';
    $this->kode_komoditi = '';
    $this->nama = '';
    $this->satuan_dasar = '';
    $this->kalori_per_100g = '';
    $this->protein_per_100g = '';
    $this->lemak_per_100g = '';
    $this->karbohidrat_per_100g = '';
    $this->serat_per_100g = '';
    $this->vitamin_c_per_100g = '';
    $this->zat_besi_per_100g = '';
    $this->kalsium_per_100g = '';
    $this->musim_panen = '';
    $this->asal_produksi = '';
    $this->shelf_life_hari = '';
    $this->harga_rata_per_kg = '';
    $this->editingKomoditi = null;
    $this->resetErrorBag();
    }

    public function render()
    {
        $perPage = (int) $this->perPage;
        if (! in_array($perPage, $this->perPageOptions, true)) {
            $perPage = 10;
        }

        $komoditis = Komoditi::when($this->search, function ($query) {
                $query->where(function($q) {
                    $q->where('kode_kelompok', 'like', '%' . $this->search . '%')
                      ->orWhere('kode_komoditi', 'like', '%' . $this->search . '%')
                      ->orWhere('nama', 'like', '%' . $this->search . '%');
                });
        })->paginate($perPage);

        $kelompoks = Kelompok::all();

        return view('livewire.admin.komoditi-management', [
            'komoditis' => $komoditis,
            'kelompoks' => $kelompoks,
        ]);
    }

    public function export()
    {
        $format = strtolower($this->exportFormat ?? 'xlsx');
        if (! in_array($format, ['xlsx','csv'], true)) {
            $format = 'xlsx';
        }

        $filename = 'komoditi-' . now()->format('Ymd-His') . '.' . $format;
        
        return Excel::download(new KomoditiExport($this->search ?: null), $filename, $format === 'csv' ? \Maatwebsite\Excel\Excel::CSV : \Maatwebsite\Excel\Excel::XLSX);
    }

    public function print()
    {
        // Trigger browser print via JS listener
        $this->dispatch('print-komoditi');
    }
}
