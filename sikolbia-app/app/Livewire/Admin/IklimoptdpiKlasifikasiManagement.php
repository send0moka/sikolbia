<?php

namespace App\Livewire\Admin;

use App\Models\IklimoptdpiKlasifikasi;
use Livewire\Component;
use Livewire\WithPagination;

class IklimoptdpiKlasifikasiManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $filterVariabel = '';
    public array $perPageOptions = [5, 10, 25, 100];
    public $sortField = 'id';
    public $sortDirection = 'asc';
    // Map UI sort fields to real DB columns (some tables use 'deskripsi' instead of 'nama')
    protected array $sortableColumnMap = [
        'nama' => 'deskripsi',
    ];
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;

    // Form fields
    // DB uses 'deskripsi' column; keep 'nama' for compatibility
    public $nama = '';
    public $deskripsi = '';
    public $id_variabel = '';
    public $editingKlasifikasi = null;
    public $deletingKlasifikasi = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
        'sortField' => ['except' => 'id'],
        'sortDirection' => ['except' => 'asc'],
    ];

    protected $casts = [
        'perPage' => 'integer',
    ];

    protected $rules = [
        'deskripsi' => 'nullable|min:3|max:255',
        'nama' => 'nullable|min:3|max:255',
        'id_variabel' => 'required|exists:iklimoptdpi_variabel,id',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedPerPage($value)
    {
        if (! in_array((int)$value, $this->perPageOptions, true)) {
            $this->perPage = 10;
        }
        $this->resetPage();
    }

    public function updatedFilterVariabel()
    {
        // reset pagination and clear search when filtering by variabel
        $this->resetPage();
        if ($this->filterVariabel && $this->search) {
            $this->search = '';
        }
    }

    public function updatedSearch($value)
    {
        // reset pagination and clear variabel filter when searching
        $this->resetPage();
        if ($value && $this->filterVariabel) {
            $this->filterVariabel = '';
        }
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
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

    public function openEditModal($klasifikasiId)
    {
        $this->editingKlasifikasi = IklimoptdpiKlasifikasi::findOrFail($klasifikasiId);
        $this->deskripsi = $this->editingKlasifikasi->deskripsi ?? $this->editingKlasifikasi->nama ?? '';
        $this->nama = $this->deskripsi;
        $this->id_variabel = $this->editingKlasifikasi->id_variabel ?? '';
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->resetForm();
    }

    public function openDeleteModal($klasifikasiId)
    {
        $this->deletingKlasifikasi = IklimoptdpiKlasifikasi::findOrFail($klasifikasiId);
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->deletingKlasifikasi = null;
    }

    public function createKlasifikasi()
    {
        $this->validate();

        $value = $this->deskripsi ?: $this->nama;

        IklimoptdpiKlasifikasi::create([
            'id_variabel' => $this->id_variabel,
            'deskripsi' => $value,
        ]);

        session()->flash('message', 'Klasifikasi iklim opt dpi berhasil dibuat.');
        $this->closeCreateModal();
    }

    public function updateKlasifikasi()
    {
        $this->validate();

        $value = $this->deskripsi ?: $this->nama;

        $this->editingKlasifikasi->update([
            'id_variabel' => $this->id_variabel,
            'deskripsi' => $value,
        ]);

        session()->flash('message', 'Klasifikasi iklim opt dpi berhasil diupdate.');
        $this->closeEditModal();
    }

    public function deleteKlasifikasi()
    {
        if ($this->deletingKlasifikasi) {
            $this->deletingKlasifikasi->delete();
            session()->flash('message', 'Klasifikasi iklim opt dpi berhasil dihapus.');
            $this->closeDeleteModal();
        }
    }

    private function resetForm()
    {
        $this->nama = '';
        $this->deskripsi = '';
        $this->id_variabel = '';
        $this->editingKlasifikasi = null;
        $this->resetErrorBag();
    }

    public function render()
    {
        $query = IklimoptdpiKlasifikasi::with('variabel.topik')
            ->when($this->search, function ($q) {
                // The table stores the label in 'deskripsi'. Avoid querying 'nama' which may not exist.
                $q->where('deskripsi', 'like', '%' . $this->search . '%')
                ->orWherehas('variabel', function ($variabelQuery) {
                    $variabelQuery->where('deskripsi', 'like', '%' . $this->search . '%')
                    ->orWhereHas('topik', function ($topikQuery) {
                        $topikQuery->where('deskripsi', 'like', '%' . $this->search . '%');
                    });
                });
            });

        if ($this->filterVariabel) {
            $query->where('id_variabel', $this->filterVariabel);
        }

        $orderByField = $this->sortableColumnMap[$this->sortField] ?? $this->sortField;

        $klasifikasis = $query->orderBy($orderByField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.iklimoptdpi-klasifikasi-management', [
            'klasifikasis' => $klasifikasis,
            'variabels' => \App\Models\IklimoptdpiVariabel::with('topik')->orderBy('deskripsi')->get(),
        ]);
    }
}
