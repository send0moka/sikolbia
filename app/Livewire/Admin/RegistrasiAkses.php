<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\RegistrasiAkses as RegistrasiAksesModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RegistrasiAkses extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = '';
    public $filterTipe = '';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';

    // Modal states
    public $showDetailModal = false;
    public $showActionModal = false;
    public $selectedRegistrasi = null;
    public $actionType = '';
    public $adminCatatan = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'filterStatus' => ['except' => ''],
        'filterTipe' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function updatingFilterTipe()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function showDetail($registrasiId)
    {
        Log::info('showDetail called with ID: ' . $registrasiId);
        $this->selectedRegistrasi = RegistrasiAksesModel::findOrFail($registrasiId);
        $this->showDetailModal = true;
        Log::info('Modal state set: showDetailModal = true');
    }

    public function showActionModal($registrasiId, $action)
    {
        $this->selectedRegistrasi = RegistrasiAksesModel::findOrFail($registrasiId);
        $this->actionType = $action;
        $this->adminCatatan = '';
        $this->showActionModal = true;
    }

    public function processAction()
    {
        $this->validate([
            'adminCatatan' => 'nullable|string|max:500'
        ]);

        $adminId = Auth::id();

        switch ($this->actionType) {
            case 'approve':
                $this->selectedRegistrasi->approve($adminId, $this->adminCatatan);
                $message = 'Registrasi berhasil disetujui.';
                break;
            case 'reject':
                $this->selectedRegistrasi->reject($adminId, $this->adminCatatan);
                $message = 'Registrasi berhasil ditolak.';
                break;
            case 'need_documents':
                $this->selectedRegistrasi->needDocuments($adminId, $this->adminCatatan);
                $message = 'Status diubah menjadi perlu dokumen tambahan.';
                break;
            default:
                $message = 'Aksi tidak valid.';
        }

        $this->showActionModal = false;
        $this->selectedRegistrasi = null;
        $this->adminCatatan = '';

        session()->flash('message', $message);
    }

    public function closeModals()
    {
        $this->showDetailModal = false;
        $this->showActionModal = false;
        $this->selectedRegistrasi = null;
        $this->adminCatatan = '';
    }

    public function closeModal()
    {
        $this->closeModals();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterStatus = '';
        $this->filterTipe = '';
        $this->resetPage();
    }

    public function viewDetail($registrasiId)
    {
        Log::info('viewDetail called with ID: ' . $registrasiId);
        $this->showDetail($registrasiId);
    }

    public function approve($registrasiId)
    {
        $registrasi = RegistrasiAksesModel::findOrFail($registrasiId);
        $registrasi->approve(Auth::id(), 'Disetujui melalui dashboard admin');
        
        session()->flash('message', 'Registrasi berhasil disetujui.');
        $this->closeModals();
    }

    public function reject($registrasiId)
    {
        $registrasi = RegistrasiAksesModel::findOrFail($registrasiId);
        $registrasi->reject(Auth::id(), 'Ditolak melalui dashboard admin');
        
        session()->flash('message', 'Registrasi berhasil ditolak.');
        $this->closeModals();
    }

    public function needDocuments($registrasiId)
    {
        $registrasi = RegistrasiAksesModel::findOrFail($registrasiId);
        $registrasi->needDocuments(Auth::id(), 'Membutuhkan dokumen tambahan');
        
        session()->flash('message', 'Status diubah menjadi butuh dokumen tambahan.');
        $this->closeModals();
    }

    public function render()
    {
        $query = RegistrasiAksesModel::query();

        // Apply search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('nama_lengkap', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('instansi', 'like', '%' . $this->search . '%')
                  ->orWhere('institusi', 'like', '%' . $this->search . '%');
            });
        }

        // Apply filters
        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterTipe) {
            $query->where('tipe_akses', $this->filterTipe);
        }

        // Apply sorting
        $query->orderBy($this->sortBy, $this->sortDirection);

        $registrasiList = $query->with('reviewer')->paginate(10);

        // Statistics
        $stats = [
            'total' => RegistrasiAksesModel::count(),
            'pending' => RegistrasiAksesModel::where('status', 'pending')->count(),
            'approved' => RegistrasiAksesModel::where('status', 'approved')->count(),
            'rejected' => RegistrasiAksesModel::where('status', 'rejected')->count(),
        ];

        return view('livewire.admin.registrasi-akses', [
            'registrasiList' => $registrasiList,
            'stats' => $stats
        ]);
    }
}
