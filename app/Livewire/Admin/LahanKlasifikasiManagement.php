<?php

namespace App\Livewire\Admin;

use App\Models\LahanKlasifikasi;
use Livewire\Component;
use Livewire\WithPagination;

class LahanKlasifikasiManagement extends Component
{
    use WithPagination;

    // Sorting
    public $sortField = 'id';
    public $sortDirection = 'asc';

    public $search = '';
    public $perPage = 10;
    public $perPageOptions = [10, 25, 50, 100];

    // Modal states
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;

    // Form fields
    public $nama = '';

    // Edit/Delete tracking
    public $editingKlasifikasi;
    public $deletingKlasifikasi;

    protected $rules = [
        'nama' => 'required|string|max:255',
    ];

    protected $messages = [
        'nama.required' => 'Nama klasifikasi harus diisi.',
        'nama.max' => 'Nama klasifikasi maksimal 255 karakter.',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
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

    public function openEditModal($id)
    {
        $this->editingKlasifikasi = LahanKlasifikasi::findOrFail($id);
        $this->nama = $this->editingKlasifikasi->nama;
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->resetForm();
        $this->editingKlasifikasi = null;
    }

    public function openDeleteModal($id)
    {
        $this->deletingKlasifikasi = LahanKlasifikasi::findOrFail($id);
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

        LahanKlasifikasi::create([
            'nama' => $this->nama,
        ]);

        session()->flash('message', 'Klasifikasi lahan berhasil ditambahkan.');
        $this->closeCreateModal();
    }

    public function updateKlasifikasi()
    {
        $this->validate();

        $this->editingKlasifikasi->update([
            'nama' => $this->nama,
        ]);

        session()->flash('message', 'Klasifikasi lahan berhasil diperbarui.');
        $this->closeEditModal();
    }

    public function deleteKlasifikasi()
    {
        $this->deletingKlasifikasi->delete();
        session()->flash('message', 'Klasifikasi lahan berhasil dihapus.');
        $this->closeDeleteModal();
    }

    private function resetForm()
    {
        $this->nama = '';
        $this->resetErrorBag();
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

    public function render()
    {
        $klasifikasis = LahanKlasifikasi::query()
            ->when($this->search, function ($query) {
                $query->where('nama', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.lahan-klasifikasi-management', [
            'klasifikasis' => $klasifikasis,
        ]);
    }
}
