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
    // DB column is 'deskripsi' but UI previously used 'nama' — keep both for compatibility
    public $nama = '';
    public $deskripsi = '';
    public $id_topik = '';
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
        'deskripsi' => 'nullable|min:3|max:255',
        'nama' => 'nullable|min:3|max:255',
        'id_topik' => 'required|exists:iklimoptdpi_topik,id',
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
        $this->deskripsi = $this->editingVariabel->deskripsi ?? $this->editingVariabel->nama ?? '';
        $this->nama = $this->deskripsi;
        $this->id_topik = $this->editingVariabel->id_topik ?? '';
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

        $value = $this->deskripsi ?: $this->nama;

        IklimoptdpiVariabel::create([
            'id_topik' => $this->id_topik,
            'deskripsi' => $value,
            'satuan' => $this->satuan,
        ]);

        session()->flash('message', 'Variabel iklim opt dpi berhasil dibuat.');
        $this->closeCreateModal();
    }

    public function updateVariabel()
    {
        $this->validate();

        $value = $this->deskripsi ?: $this->nama;

        $this->editingVariabel->update([
            'id_topik' => $this->id_topik,
            'deskripsi' => $value,
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
        $this->deskripsi = '';
        $this->id_topik = '';
        $this->satuan = '';
        $this->editingVariabel = null;
        $this->resetErrorBag();
    }

    public function render()
    {
        $variabels = IklimoptdpiVariabel::when($this->search, function ($query) {
                $query->where('deskripsi', 'like', '%' . $this->search . '%')
                    ->orWhere('satuan', 'like', '%' . $this->search . '%')
                    ->orWhereHas('topik', function ($topikQuery) {
                        $topikQuery->where('deskripsi', 'like', '%' . $this->search . '%');
                    });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.iklimoptdpi-variabel-management', [
            'variabels' => $variabels,
            'topiks' => \App\Models\IklimoptdpiTopik::orderBy('deskripsi')->get(),
        ]);
    }
}
