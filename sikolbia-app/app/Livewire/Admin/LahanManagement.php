<?php

namespace App\Livewire\Admin;

use App\Models\LahanTopik;
use App\Models\LahanVariabel;
use App\Models\LahanKlasifikasi;
use App\Models\LahanData;
use App\Exports\LahanExport;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class LahanManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public array $perPageOptions = [5, 10, 25, 100];
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $showFilters = false;

    // Form fields
    public $nilai = '';
    public $id_wilayah = '';
    public $tingkat_wilayah = ''; // 'nasional' atau 'provinsi'
    public $id_provinsi = ''; // untuk filter kabupaten/kota
    public $id_bulan = '';
    public $tahun = '';
    public $status = '';
    public $id_variabel = '';
    public $id_klasifikasi = '';
    public $editingLahan = null;
    public $deletingLahan = null;
    public $exportFormat = 'xlsx';
    
    // Sorting
    public $sortField = 'id';
    public $sortDirection = 'asc';
    
    // Filters
    public $filterTahun = '';
    public $filterTopik = '';
    public $filterVariabel = '';
    public $filterKlasifikasi = '';
    public $filterStatus = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
        'filterTahun' => ['except' => ''],
        'filterTopik' => ['except' => ''],
        'filterVariabel' => ['except' => ''],
        'filterKlasifikasi' => ['except' => ''],
        'filterStatus' => ['except' => ''],
    ];

    protected $casts = [
        'perPage' => 'integer',
    ];

    protected $rules = [
        'nilai' => 'required|numeric|min:0',
        'id_wilayah' => 'required|exists:wilayah,id',
        'id_bulan' => 'required|exists:bulan,id',
        'tahun' => 'required|integer|min:2000|max:2030',
        'status' => 'nullable|in:Aktif,Tidak Aktif,Dalam Proses,Selesai,Tertunda',
        'id_variabel' => 'required|exists:lahan_variabel,id',
        'id_klasifikasi' => 'required|exists:lahan_klasifikasi,id',
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

    public function openEditModal($lahanId)
    {
        $this->editingLahan = LahanData::findOrFail($lahanId);
        $this->nilai = $this->editingLahan->nilai;
        $this->id_wilayah = $this->editingLahan->id_wilayah;
        $this->id_bulan = $this->editingLahan->id_bulan;
        $this->tahun = $this->editingLahan->tahun;
        $this->status = $this->editingLahan->status;
        $this->id_variabel = $this->editingLahan->id_variabel;
        $this->id_klasifikasi = $this->editingLahan->id_klasifikasi;
        
        // Set tingkat wilayah dan provinsi berdasarkan wilayah yang dipilih
        if ($this->id_wilayah) {
            $wilayah = \App\Models\Wilayah::find($this->id_wilayah);
            if ($wilayah) {
                if ($wilayah->id_kategori == 1) { // Provinsi
                    $this->tingkat_wilayah = 'nasional';
                } else { // Kabupaten/Kota
                    $this->tingkat_wilayah = 'provinsi';
                    $this->id_provinsi = $wilayah->id_parent;
                }
            }
        }
        
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->resetForm();
    }

    public function openDeleteModal($lahanId)
    {
        $this->deletingLahan = LahanData::findOrFail($lahanId);
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->deletingLahan = null;
    }

    public function createLahan()
    {
        $this->validate();

        LahanData::create([
            'nilai' => $this->nilai,
            'id_wilayah' => $this->id_wilayah,
            'id_bulan' => $this->id_bulan,
            'tahun' => $this->tahun,
            'status' => $this->status,
            'id_variabel' => $this->id_variabel,
            'id_klasifikasi' => $this->id_klasifikasi,
        ]);

        session()->flash('message', 'Data lahan berhasil dibuat.');
        $this->closeCreateModal();
    }

    public function updateLahan()
    {
        $this->validate();

        $this->editingLahan->update([
            'nilai' => $this->nilai,
            'id_wilayah' => $this->id_wilayah,
            'id_bulan' => $this->id_bulan,
            'tahun' => $this->tahun,
            'status' => $this->status,
            'id_variabel' => $this->id_variabel,
            'id_klasifikasi' => $this->id_klasifikasi,
        ]);

        session()->flash('message', 'Data lahan berhasil diupdate.');
        $this->closeEditModal();
    }

    public function deleteLahan()
    {
        if ($this->deletingLahan) {
            $this->deletingLahan->delete();
            session()->flash('message', 'Data lahan berhasil dihapus.');
            $this->closeDeleteModal();
        }
    }

    private function resetForm()
    {
        $this->nilai = '';
        $this->id_wilayah = '';
        $this->tingkat_wilayah = '';
        $this->id_provinsi = '';
        $this->id_bulan = '';
        $this->tahun = '';
        $this->status = '';
        $this->id_variabel = '';
        $this->id_klasifikasi = '';
        $this->editingLahan = null;
        $this->resetErrorBag();
    }

    public function updatedTingkatWilayah()
    {
        $this->id_wilayah = '';
        $this->id_provinsi = '';
    }

    public function updatedIdProvinsi()
    {
        $this->id_wilayah = '';
    }

    public function updatedIdVariabel()
    {
        $this->id_klasifikasi = '';
    }

    public function getProvinsiOptions()
    {
        return \App\Models\Wilayah::where('id_kategori', 1)->orderBy('nama')->get();
    }

    public function getKabupatenKotaOptions()
    {
        if ($this->id_provinsi) {
            return \App\Models\Wilayah::where('id_parent', $this->id_provinsi)->orderBy('nama')->get();
        }
        return collect();
    }

    public function getKlasifikasiOptions()
    {
        if ($this->id_variabel) {
            return \App\Models\LahanKlasifikasi::where('id_variabel', $this->id_variabel)->orderBy('sorter')->get();
        }
        return collect();
    }
    
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }
    
    public function toggleFilters()
    {
        $this->showFilters = !$this->showFilters;
    }
    
    public function clearFilters()
    {
        $this->reset([
            'filterTahun', 
            'filterTopik', 
            'filterVariabel', 
            'filterKlasifikasi', 
            'filterStatus'
        ]);
    }
    
    public function resetSort()
    {
        $this->reset(['sortField', 'sortDirection']);
    }

    public function export()
    {
        $fileName = 'data-lahan-' . now()->format('Y-m-d-H-i-s') . '.' . $this->exportFormat;
        return Excel::download(new LahanExport($this->search), $fileName);
    }


    public function render()
    {
        $query = LahanData::with(['topik', 'variabel', 'klasifikasi', 'wilayah'])
            ->when($this->search, function ($query) {
                $search = '%' . $this->search . '%';
                $query->where('tahun', 'like', $search)
                    ->orWhere('nilai', 'like', $search)
                    ->orWhere('status', 'like', $search)
                    ->orWhereHas('topik', function($q) use ($search) {
                        $q->where('deskripsi', 'like', $search);
                    })
                    ->orWhereHas('variabel', function($q) use ($search) {
                        $q->where('deskripsi', 'like', $search);
                    })
                    ->orWhereHas('klasifikasi', function($q) use ($search) {
                        $q->where('deskripsi', 'like', $search);
                    })
                    ->orWhereHas('wilayah', function($q) use ($search) {
                        $q->where('nama', 'like', $search);
                    });
            })
            ->when($this->filterTahun, function ($query) {
                $query->where('tahun', $this->filterTahun);
            })
            ->when($this->filterTopik, function ($query) {
                $query->whereHas('variabel', function($q) {
                    $q->where('id_topik', $this->filterTopik);
                });
            })
            ->when($this->filterVariabel, function ($query) {
                $query->where('id_variabel', $this->filterVariabel);
            })
            ->when($this->filterKlasifikasi, function ($query) {
                $query->where('id_klasifikasi', $this->filterKlasifikasi);
            })
            ->when($this->filterStatus, function ($query) {
                $query->where('status', $this->filterStatus);
            });

        // Apply sorting
        if ($this->sortField) {
            // allow sorting by friendly keys: topik, variabel, klasifikasi, wilayah, tahun, nilai, id
            switch ($this->sortField) {
                case 'topik':
                    // join lahan_variabel -> lahan_topik via lahan_variabel.id_topik
                    $query->leftJoin('lahan_variabel', 'lahan_data.id_variabel', '=', 'lahan_variabel.id')
                          ->leftJoin('lahan_topik', 'lahan_variabel.id_topik', '=', 'lahan_topik.id')
                          ->select('lahan_data.*')
                          ->orderBy('lahan_topik.deskripsi', $this->sortDirection);
                    break;
                case 'variabel':
                    $query->leftJoin('lahan_variabel', 'lahan_data.id_variabel', '=', 'lahan_variabel.id')
                          ->select('lahan_data.*')
                          ->orderBy('lahan_variabel.deskripsi', $this->sortDirection);
                    break;
                case 'klasifikasi':
                    $query->leftJoin('lahan_klasifikasi', 'lahan_data.id_klasifikasi', '=', 'lahan_klasifikasi.id')
                          ->select('lahan_data.*')
                          ->orderBy('lahan_klasifikasi.deskripsi', $this->sortDirection);
                    break;
                case 'wilayah':
                    $query->leftJoin('wilayah', 'lahan_data.id_wilayah', '=', 'wilayah.id')
                          ->select('lahan_data.*')
                          ->orderBy('wilayah.nama', $this->sortDirection);
                    break;
                default:
                    // default to ordering by lahan_data column if exists
                    $allowed = ['id', 'tahun', 'nilai', 'status'];
                    if (in_array($this->sortField, $allowed, true)) {
                        $query->orderBy('lahan_data.' . $this->sortField, $this->sortDirection);
                    }
                    break;
            }
        }

        $lahans = $query->paginate($this->perPage);

        $topiks = LahanTopik::all();
        $variabels = LahanVariabel::all();
        $klasifikasis = LahanKlasifikasi::all();

        return view('livewire.admin.lahan-management', [
            'lahans' => $lahans,
            'topiks' => LahanTopik::orderBy('deskripsi')->get(),
            'variabels' => LahanVariabel::orderBy('deskripsi')->get(),
            'klasifikasis' => LahanKlasifikasi::orderBy('deskripsi')->get(),
            'klasifikasiOptions' => $this->getKlasifikasiOptions(),
            'wilayahs' => \App\Models\Wilayah::orderBy('nama')->get(),
            'provinsiOptions' => $this->getProvinsiOptions(),
            'kabupatenKotaOptions' => $this->getKabupatenKotaOptions(),
            'bulans' => \App\Models\Bulan::orderBy('id')->get(),
            'tahunOptions' => LahanData::select('tahun')
                ->distinct()
                ->orderBy('tahun', 'desc')
                ->pluck('tahun'),
            'statusOptions' => ['Aktif', 'Tidak Aktif', 'Dalam Proses', 'Selesai', 'Tertunda']
        ]);
    }
}
