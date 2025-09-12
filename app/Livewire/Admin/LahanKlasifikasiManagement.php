<?php

namespace App\Livewire\Admin;

use App\Models\LahanKlasifikasi;
use App\Models\LahanVariabel;
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
    public $id_variabel = null;

    // Lists
    public $variabelOptions = [];

    // Edit/Delete tracking
    public $editingKlasifikasi;
    public $deletingKlasifikasi;

    protected $rules = [
        'nama' => 'required|string|max:255',
    'id_variabel' => 'required|integer|exists:lahan_variabel,id',
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
    $this->loadVariabelOptions();
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
    $this->id_variabel = $this->editingKlasifikasi->id_variabel;
    $this->loadVariabelOptions();
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
            'deskripsi' => $this->nama,
            'id_variabel' => $this->id_variabel,
        ]);

        session()->flash('message', 'Klasifikasi lahan berhasil ditambahkan.');
        $this->closeCreateModal();
    }

    public function updateKlasifikasi()
    {
        $this->validate();

        $this->editingKlasifikasi->update([
            'deskripsi' => $this->nama,
            'id_variabel' => $this->id_variabel,
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
        $this->id_variabel = null;
        $this->resetErrorBag();
    }

    private function loadVariabelOptions()
    {
        $this->variabelOptions = LahanVariabel::orderBy('deskripsi')->get();
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
                // search actual DB column `deskripsi`
                $query->where('deskripsi', 'like', '%' . $this->search . '%');
            })
            ->with('variabel')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.lahan-klasifikasi-management', [
            'klasifikasis' => $klasifikasis,
        ]);
    }
}
