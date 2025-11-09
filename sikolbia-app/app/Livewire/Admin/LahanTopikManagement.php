<?php

namespace App\Livewire\Admin;

use App\Models\LahanTopik;
use Livewire\Component;
use Livewire\WithPagination;

class LahanTopikManagement extends Component
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
    public $editingTopik;
    public $deletingTopik;

    protected $rules = [
        'nama' => 'required|string|max:255',
    ];

    protected $messages = [
        'nama.required' => 'Nama topik harus diisi.',
        'nama.max' => 'Nama topik maksimal 255 karakter.',
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
        $this->editingTopik = LahanTopik::findOrFail($id);
        $this->nama = $this->editingTopik->nama;
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->resetForm();
        $this->editingTopik = null;
    }

    public function openDeleteModal($id)
    {
        $this->deletingTopik = LahanTopik::findOrFail($id);
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->deletingTopik = null;
    }

    public function createTopik()
    {
        $this->validate();

        LahanTopik::create([
            'nama' => $this->nama,
        ]);

        session()->flash('message', 'Topik lahan berhasil ditambahkan.');
        $this->closeCreateModal();
    }

    public function updateTopik()
    {
        $this->validate();

        $this->editingTopik->update([
            'nama' => $this->nama,
        ]);

        session()->flash('message', 'Topik lahan berhasil diperbarui.');
        $this->closeEditModal();
    }

    public function deleteTopik()
    {
        $this->deletingTopik->delete();
        session()->flash('message', 'Topik lahan berhasil dihapus.');
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
        $topiks = LahanTopik::query()
            ->when($this->search, function ($query) {
                $query->where('nama', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.lahan-topik-management', [
            'topiks' => $topiks,
        ]);
    }
}
