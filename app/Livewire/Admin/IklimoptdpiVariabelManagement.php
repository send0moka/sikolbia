<?php

namespace App\Livewire\Admin;

use App\Models\IklimoptdpiVariabel;
use Livewire\Component;
use Livewire\WithPagination;

class IklimoptdpiVariabelManagement extends Component
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
    public $satuan = '';
    public $editingVariabel = null;
    public $deletingVariabel = null;

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
        'satuan' => 'required|min:1|max:50',
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

    public function openEditModal($variabelId)
    {
        $this->editingVariabel = IklimoptdpiVariabel::findOrFail($variabelId);
        $this->nama = $this->editingVariabel->nama;
        $this->satuan = $this->editingVariabel->satuan;
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->resetForm();
    }

    public function openDeleteModal($variabelId)
    {
        $this->deletingVariabel = IklimoptdpiVariabel::findOrFail($variabelId);
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

        IklimoptdpiVariabel::create([
            'nama' => $this->nama,
            'satuan' => $this->satuan,
        ]);

        session()->flash('message', 'Variabel iklim opt dpi berhasil dibuat.');
        $this->closeCreateModal();
    }

    public function updateVariabel()
    {
        $this->validate();

        $this->editingVariabel->update([
            'nama' => $this->nama,
            'satuan' => $this->satuan,
        ]);

        session()->flash('message', 'Variabel iklim opt dpi berhasil diupdate.');
        $this->closeEditModal();
    }

    public function deleteVariabel()
    {
        if ($this->deletingVariabel) {
            $this->deletingVariabel->delete();
            session()->flash('message', 'Variabel iklim opt dpi berhasil dihapus.');
            $this->closeDeleteModal();
        }
    }

    private function resetForm()
    {
        $this->nama = '';
        $this->satuan = '';
        $this->editingVariabel = null;
        $this->resetErrorBag();
    }

    public function render()
    {
        $variabels = IklimoptdpiVariabel::when($this->search, function ($query) {
                $query->where('nama', 'like', '%' . $this->search . '%')
                    ->orWhere('satuan', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.iklimoptdpi-variabel-management', [
            'variabels' => $variabels,
        ]);
    }
}
