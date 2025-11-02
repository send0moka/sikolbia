<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\RegistrasiAkses as RegistrasiAksesModel;
use App\Mail\RegistrasiApprovedMail;
use App\Mail\RegistrasiRejectedMail;
use App\Mail\RegistrasiNeedDocumentsMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
    public $catatanAdmin = ''; // For detail modal input

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
        // Always fetch fresh data from database to avoid cache issues
        $this->selectedRegistrasi = RegistrasiAksesModel::with('reviewer')->findOrFail($registrasiId);
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
        $this->catatanAdmin = ''; // Reset catatan when opening modal
        $this->showDetail($registrasiId);
    }

    public function approve($registrasiId)
    {
        $registrasi = RegistrasiAksesModel::findOrFail($registrasiId);
        
        // Use custom catatan if provided, otherwise use default
        $catatan = !empty($this->catatanAdmin) 
            ? $this->catatanAdmin 
            : 'Selamat! Registrasi Anda telah disetujui. Anda sekarang dapat mengakses sistem SIKOLBIA.';
        
        try {
            // Create User account
            $user = $this->createUserFromRegistrasi($registrasi);
            
            // Approve registration
            $registrasi->approve(Auth::id(), $catatan);
            
            // Store user_id in registrasi
            $registrasi->update(['user_id' => $user->id]);
            
            // Send email notification with credentials
            Mail::to($registrasi->email)->send(new RegistrasiApprovedMail($registrasi, $user->password_plain));
            Log::info('User account created and approval email sent to: ' . $registrasi->email);
            
            session()->flash('message', 'Registrasi berhasil disetujui, akun user telah dibuat, dan email notifikasi telah dikirim.');
        } catch (\Exception $e) {
            Log::error('Failed to approve registration: ' . $e->getMessage());
            session()->flash('error', 'Gagal menyetujui registrasi: ' . $e->getMessage());
        }
        
        $this->catatanAdmin = ''; // Reset after action
        $this->closeModals();
    }
    
    private function createUserFromRegistrasi($registrasi)
    {
        // Generate username from email
        $username = explode('@', $registrasi->email)[0];
        $baseUsername = $username;
        $counter = 1;
        
        // Ensure unique username
        while (\App\Models\User::where('email', $username . '@' . explode('@', $registrasi->email)[1])->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }
        
        // Generate random password
        $password = 'SIKOLBIA' . rand(1000, 9999);
        
        // Create user
        $user = \App\Models\User::create([
            'name' => $registrasi->nama_lengkap,
            'email' => $registrasi->email,
            'password' => bcrypt($password),
            'email_verified_at' => now(),
        ]);
        
        // Store plain password temporarily for email
        $user->password_plain = $password;
        
        // Assign role based on tipe_akses
        $roleName = $registrasi->tipe_akses === 'pemerintah' ? 'pemerintah' : 'akademisi';
        
        if (\Spatie\Permission\Models\Role::where('name', $roleName)->exists()) {
            $user->assignRole($roleName);
        } else {
            // Fallback to default role if specific role doesn't exist
            Log::warning("Role '$roleName' not found, assigning 'user' role instead");
            if (\Spatie\Permission\Models\Role::where('name', 'user')->exists()) {
                $user->assignRole('user');
            }
        }
        
        Log::info('User created', [
            'user_id' => $user->id,
            'email' => $user->email,
            'role' => $roleName,
        ]);
        
        return $user;
    }

    public function reject($registrasiId)
    {
        $registrasi = RegistrasiAksesModel::findOrFail($registrasiId);
        
        // Use custom catatan if provided, otherwise use default
        $catatan = !empty($this->catatanAdmin) 
            ? $this->catatanAdmin 
            : 'Mohon maaf, registrasi Anda tidak dapat disetujui saat ini. Silakan periksa kembali data yang Anda kirimkan atau hubungi admin untuk informasi lebih lanjut.';
        
        $registrasi->reject(Auth::id(), $catatan);
        
        // Send email notification
        try {
            Mail::to($registrasi->email)->send(new RegistrasiRejectedMail($registrasi));
            Log::info('Rejection email sent to: ' . $registrasi->email);
        } catch (\Exception $e) {
            Log::error('Failed to send rejection email: ' . $e->getMessage());
        }
        
        session()->flash('message', 'Registrasi berhasil ditolak dan email notifikasi telah dikirim.');
        $this->catatanAdmin = ''; // Reset after action
        $this->closeModals();
    }

    public function needDocuments($registrasiId)
    {
        $registrasi = RegistrasiAksesModel::findOrFail($registrasiId);
        
        // Use custom catatan if provided, otherwise use default
        $catatan = !empty($this->catatanAdmin) 
            ? $this->catatanAdmin 
            : 'Untuk melanjutkan proses verifikasi, kami memerlukan dokumen atau informasi tambahan dari Anda. Silakan hubungi kami untuk detail lebih lanjut.';
        
        // Generate resubmit token (valid for 30 days)
        $token = bin2hex(random_bytes(32));
        $registrasi->update([
            'resubmit_token' => $token,
            'resubmit_token_expires_at' => now()->addDays(30)
        ]);
        
        $registrasi->needDocuments(Auth::id(), $catatan);
        
        // Send email notification
        try {
            Mail::to($registrasi->email)->send(new RegistrasiNeedDocumentsMail($registrasi));
            Log::info('Need documents email sent to: ' . $registrasi->email);
        } catch (\Exception $e) {
            Log::error('Failed to send need documents email: ' . $e->getMessage());
        }
        
        session()->flash('message', 'Status diubah menjadi butuh dokumen tambahan dan email notifikasi telah dikirim.');
        $this->catatanAdmin = ''; // Reset after action
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
