<?php

namespace App\Livewire\Admin;

use App\Models\LahanVariabel;
use Livewire\Component;
use Livewire\WithPagination;

class LahanVariabelManagement extends Component
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
    public $satuan = '';

    // Edit/Delete tracking
    public $editingVariabel;
    public $deletingVariabel;

    protected $rules = [
        'nama' => 'required|string|max:255',
        'satuan' => 'required|string|max:50',
    ];

    protected $messages = [
        'nama.required' => 'Nama variabel harus diisi.',
        'nama.max' => 'Nama variabel maksimal 255 karakter.',
        'satuan.required' => 'Satuan harus diisi.',
        'satuan.max' => 'Satuan maksimal 50 karakter.',
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
        $this->editingVariabel = LahanVariabel::findOrFail($id);
        $this->nama = $this->editingVariabel->nama;
        $this->satuan = $this->editingVariabel->satuan;
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->resetForm();
        $this->editingVariabel = null;
    }

    public function openDeleteModal($id)
    {
        $this->deletingVariabel = LahanVariabel::findOrFail($id);
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->deletingVariabel = null;
    }

    public function createVariabel()
    {
        $this->validate();

        LahanVariabel::create([
            'nama' => $this->nama,
            'satuan' => $this->satuan,
        ]);

        session()->flash('message', 'Variabel lahan berhasil ditambahkan.');
        $this->closeCreateModal();
    }

    public function updateVariabel()
    {
        $this->validate();

        $this->editingVariabel->update([
            'nama' => $this->nama,
            'satuan' => $this->satuan,
        ]);

        session()->flash('message', 'Variabel lahan berhasil diperbarui.');
        $this->closeEditModal();
    }

    public function deleteVariabel()
    {
        $this->deletingVariabel->delete();
        session()->flash('message', 'Variabel lahan berhasil dihapus.');
        $this->closeDeleteModal();
    }

    private function resetForm()
    {
        $this->nama = '';
        $this->satuan = '';
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
        $variabels = LahanVariabel::query()
            ->when($this->search, function ($query) {
                $query->where('nama', 'like', '%' . $this->search . '%')
                      ->orWhere('satuan', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.lahan-variabel-management', [
            'variabels' => $variabels,
        ]);
    }
}
