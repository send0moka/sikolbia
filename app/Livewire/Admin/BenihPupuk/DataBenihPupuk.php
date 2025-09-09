<?php

namespace App\Livewire\Admin\BenihPupuk;

use App\Models\BenihPupukData;
use App\Models\BenihPupukTopik;
use App\Models\BenihPupukVariabel;
use App\Models\BenihPupukKlasifikasi;
use App\Models\BenihPupukWilayah;
use App\Models\BenihPupukBulan;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class DataBenihPupuk extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';
    
    #[Url]
    public $tahunFilter = '';
    
    #[Url]
    public $bulanFilter = '';
    
    #[Url]
    public $wilayahFilter = '';
    
    #[Url]
    public $variabelFilter = '';
    
    #[Url]
    public $klasifikasiFilter = '';
    
    #[Url]
    public $statusFilter = '';
    
    #[Url]
    public $sortBy = 'tahun';
    
    #[Url]
    public $sortDir = 'desc';

    public $perPage = 10;
    public array $perPageOptions = [5, 10, 25, 100];
    public $showModal = false;
    public $editMode = false;
    public $deleteId = null;
    public $showFilters = false;
    public $editingId = null;
    public $exportFormat = 'xlsx';
    public $tahun = '';
    public $id_bulan = '';
    public $id_wilayah = '';
    public $id_variabel = '';
    public $id_klasifikasi = '';
    public $nilai = '';
    public $status = 'A';

    protected $rules = [
        'tahun' => 'required|integer|min:2000|max:2050',
        'id_bulan' => 'required|exists:benih_pupuk_bulan,id',
        'id_wilayah' => 'required|exists:benih_pupuk_wilayah,id',
        'id_variabel' => 'required|exists:benih_pupuk_variabel,id',
        'id_klasifikasi' => 'required|exists:benih_pupuk_klasifikasi,id',
        'nilai' => 'nullable|numeric',
        'status' => 'required|in:A,I,D',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedTahunFilter()
    {
        $this->resetPage();
    }

    public function updatedBulanFilter()
    {
        $this->resetPage();
    }

    public function updatedWilayahFilter()
    {
        $this->resetPage();
    }

    public function updatedVariabelFilter()
    {
        $this->resetPage();
    }

    public function updatedKlasifikasiFilter()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
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

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDir = 'asc';
        }
        $this->resetPage();
    }

    public function toggleFilters()
    {
        $this->showFilters = !$this->showFilters;
    }

    public function resetSort()
    {
        $this->sortBy = 'tahun';
        $this->sortDir = 'desc';
        $this->resetPage();
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $data = BenihPupukData::findOrFail($id);
        $this->editingId = $id;
        $this->tahun = $data->tahun;
        $this->id_bulan = $data->id_bulan;
        $this->id_wilayah = $data->id_wilayah;
        $this->id_variabel = $data->id_variabel;
        $this->id_klasifikasi = $data->id_klasifikasi;
        $this->nilai = $data->nilai;
        $this->status = $data->status;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'tahun' => $this->tahun,
            'id_bulan' => $this->id_bulan,
            'id_wilayah' => $this->id_wilayah,
            'id_variabel' => $this->id_variabel,
            'id_klasifikasi' => $this->id_klasifikasi,
            'nilai' => $this->nilai,
            'status' => $this->status,
            'date_modified' => now(),
        ];

        if ($this->editingId) {
            BenihPupukData::find($this->editingId)->update($data);
            session()->flash('message', 'Data berhasil diperbarui.');
        } else {
            $data['date_created'] = now();
            BenihPupukData::create($data);
            session()->flash('message', 'Data berhasil ditambahkan.');
        }

        $this->closeModal();
    }

    public function delete($id)
    {
        BenihPupukData::findOrFail($id)->delete();
        session()->flash('message', 'Data berhasil dihapus.');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->editingId = null;
        $this->tahun = '';
        $this->id_bulan = '';
        $this->id_wilayah = '';
        $this->id_variabel = '';
        $this->id_klasifikasi = '';
        $this->nilai = '';
        $this->status = 'A';
        $this->resetErrorBag();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'tahunFilter', 'bulanFilter', 'wilayahFilter', 'variabelFilter', 'klasifikasiFilter', 'statusFilter']);
        $this->resetPage();
    }

    public function render()
    {
        $query = BenihPupukData::withRelations();

        // Apply filters
        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('wilayah', function ($wq) {
                    $wq->where('nama', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('variabel', function ($vq) {
                    $vq->where('deskripsi', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('klasifikasi', function ($kq) {
                    $kq->where('deskripsi', 'like', '%' . $this->search . '%');
                });
            });
        }

        if ($this->tahunFilter) {
            $query->where('tahun', $this->tahunFilter);
        }

        if ($this->bulanFilter) {
            $query->where('id_bulan', $this->bulanFilter);
        }

        if ($this->wilayahFilter) {
            $query->where('id_wilayah', $this->wilayahFilter);
        }

        if ($this->variabelFilter) {
            $query->where('id_variabel', $this->variabelFilter);
        }

        if ($this->klasifikasiFilter) {
            $query->where('id_klasifikasi', $this->klasifikasiFilter);
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        // Apply sorting
        $query->orderBy($this->sortBy, $this->sortDir);

        $data = $query->paginate($this->perPage);

        // Get filter options
        $tahunOptions = BenihPupukData::getAvailableYears();
        $bulanOptions = BenihPupukBulan::getDropdownOptions();
        $wilayahOptions = BenihPupukWilayah::getDropdownOptions();
        $variabelOptions = BenihPupukVariabel::getDropdownOptions();
        $klasifikasiOptions = BenihPupukKlasifikasi::getDropdownOptions();
        $statusOptions = BenihPupukData::getStatusOptions();

        return view('livewire.admin.benih-pupuk.data-benih-pupuk', compact(
            'data', 'tahunOptions', 'bulanOptions', 'wilayahOptions', 
            'variabelOptions', 'klasifikasiOptions', 'statusOptions'
        ));
    }
}
