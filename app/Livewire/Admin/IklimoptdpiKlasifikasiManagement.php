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
    public array $perPageOptions = [5, 10, 25, 100];
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;

    // Form fields
    public $nama = '';
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
        'nama' => 'required|min:3|max:255',
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
        $this->nama = $this->editingKlasifikasi->nama;
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

        IklimoptdpiKlasifikasi::create([
            'nama' => $this->nama,
        ]);

        session()->flash('message', 'Klasifikasi iklim opt dpi berhasil dibuat.');
        $this->closeCreateModal();
    }

    public function updateKlasifikasi()
    {
        $this->validate();

        $this->editingKlasifikasi->update([
            'nama' => $this->nama,
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
        $this->editingKlasifikasi = null;
        $this->resetErrorBag();
    }

    public function render()
    {
        $klasifikasis = IklimoptdpiKlasifikasi::when($this->search, function ($query) {
                $query->where('nama', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.iklimoptdpi-klasifikasi-management', [
            'klasifikasis' => $klasifikasis,
        ]);
    }
}
