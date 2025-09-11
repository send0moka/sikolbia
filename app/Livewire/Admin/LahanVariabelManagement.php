<?php

namespace App\Livewire\Admin;

use App\Models\LahanVariabel;
use App\Models\LahanTopik;
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
    public $id_topik = null;
    public $sorter = null;

    // Lists
    public $topikOptions = [];

    // Edit/Delete tracking
    public $editingVariabel;
    public $deletingVariabel;

    protected $rules = [
        'nama' => 'required|string|max:255',
        'satuan' => 'required|string|max:50',
    'id_topik' => 'required|integer|exists:lahan_topik,id',
    'sorter' => 'nullable|integer',
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
    $this->loadTopikOptions();
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
    $this->id_topik = $this->editingVariabel->id_topik;
    $this->sorter = $this->editingVariabel->sorter;
    $this->loadTopikOptions();
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

        // If sorter not provided, compute next sorter value
        if (empty($this->sorter)) {
            $maxSorter = LahanVariabel::max('sorter');
            $this->sorter = is_null($maxSorter) ? 1 : ($maxSorter + 1);
        }

        LahanVariabel::create([
            'id_topik' => $this->id_topik,
            'deskripsi' => $this->nama,
            'satuan' => $this->satuan,
            'sorter' => $this->sorter,
        ]);

        session()->flash('message', 'Variabel lahan berhasil ditambahkan.');
        $this->closeCreateModal();
    }

    public function updateVariabel()
    {
        $this->validate();

        $this->editingVariabel->update([
            'id_topik' => $this->id_topik,
            'deskripsi' => $this->nama,
            'satuan' => $this->satuan,
            'sorter' => $this->sorter,
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
        $this->id_topik = null;
        $this->sorter = null;
        $this->resetErrorBag();
    }

    private function loadTopikOptions()
    {
        $this->topikOptions = LahanTopik::orderBy('deskripsi')->get();
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
            // `nama` is a virtual attribute mapped to `deskripsi` in the model.
            // Use the actual DB column `deskripsi` for searching.
            $query->where('deskripsi', 'like', '%' . $this->search . '%')
                ->orWhere('satuan', 'like', '%' . $this->search . '%');
            })
        ->with('topik')
        ->orderBy($this->sortField, $this->sortDirection)
        ->paginate($this->perPage);

        return view('livewire.admin.lahan-variabel-management', [
            'variabels' => $variabels,
        ]);
    }
}
