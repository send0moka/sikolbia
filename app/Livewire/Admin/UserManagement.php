<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\RegistrasiAkses;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public array $perPageOptions = [5, 10, 25, 100];
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $showSuspendModal = false;
    public $showActivityModal = false;
    public $showDetailModal = false;
    
    public $name = '';
    public $email = '';
    public $password = '';
    public $selectedRole = '';
    public $editingUser = null;
    public $deletingUser = null;
    public $suspendingUser = null;
    public $selectedUser = null;
    public $exportFormat = 'xlsx';
    
    // Suspension fields
    public $suspendReason = '';
    public $suspendDuration = '';
    
    // Filters
    public $filterRole = '';
    public $filterStatus = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
        'filterRole' => ['except' => ''],
        'filterStatus' => ['except' => ''],
    ];

    protected $casts = [
        'perPage' => 'integer',
    ];

    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8',
        'selectedRole' => 'required',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterRole()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function updatedPerPage($value)
    {
        if (! in_array((int)$value, $this->perPageOptions, true)) {
            $this->perPage = 10; // fallback
        }
        $this->resetPage();
    }

    public function updatingPerPage($value)
    {
        // Reset pagination before value changes to avoid out-of-range page
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

    public function openEditModal($userId)
    {
        $this->editingUser = User::findOrFail($userId);
        $this->name = $this->editingUser->name;
        $this->email = $this->editingUser->email;
        $this->selectedRole = $this->editingUser->roles->first()?->name ?? '';
        $this->password = '';
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->resetForm();
    }

    public function openDeleteModal($userId)
    {
        $this->deletingUser = User::findOrFail($userId);
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->deletingUser = null;
    }

    public function openSuspendModal($userId)
    {
        $this->suspendingUser = User::findOrFail($userId);
        $this->suspendReason = '';
        $this->suspendDuration = '';
        $this->showSuspendModal = true;
    }

    public function closeSuspendModal()
    {
        $this->showSuspendModal = false;
        $this->suspendingUser = null;
        $this->suspendReason = '';
        $this->suspendDuration = '';
    }

    public function openDetailModal($userId)
    {
        $this->selectedUser = User::with(['roles', 'permissions'])->findOrFail($userId);
        
        // Get registration data if exists
        $this->selectedUser->registrasi = RegistrasiAkses::where('user_id', $userId)->first();
        
        $this->showDetailModal = true;
    }

    public function closeDetailModal()
    {
        $this->showDetailModal = false;
        $this->selectedUser = null;
    }

    public function openActivityModal($userId)
    {
        $this->selectedUser = User::findOrFail($userId);
        $this->showActivityModal = true;
    }

    public function closeActivityModal()
    {
        $this->showActivityModal = false;
        $this->selectedUser = null;
    }

    public function createUser()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => bcrypt($this->password),
                'email_verified_at' => now(),
            ]);

            if ($this->selectedRole) {
                $user->assignRole($this->selectedRole);
            }

            DB::commit();

            Log::info('User created by admin', [
                'created_user_id' => $user->id,
                'created_by' => auth()->id(),
                'role' => $this->selectedRole,
            ]);

            session()->flash('message', 'User berhasil dibuat dengan role ' . $this->selectedRole);
            $this->closeCreateModal();

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to create user: ' . $e->getMessage());
            session()->flash('error', 'Gagal membuat user: ' . $e->getMessage());
        }
    }

    public function updateUser()
    {
        $rules = [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,' . $this->editingUser->id,
            'selectedRole' => 'required',
        ];

        if (!empty($this->password)) {
            $rules['password'] = 'min:8';
        }

        $this->validate($rules);

        try {
            DB::beginTransaction();

            $this->editingUser->update([
                'name' => $this->name,
                'email' => $this->email,
            ]);

            if (!empty($this->password)) {
                $this->editingUser->update(['password' => bcrypt($this->password)]);
            }

            $this->editingUser->syncRoles([$this->selectedRole]);

            DB::commit();

            Log::info('User updated by admin', [
                'updated_user_id' => $this->editingUser->id,
                'updated_by' => auth()->id(),
                'new_role' => $this->selectedRole,
            ]);

            session()->flash('message', 'User berhasil diupdate.');
            $this->closeEditModal();

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to update user: ' . $e->getMessage());
            session()->flash('error', 'Gagal mengupdate user: ' . $e->getMessage());
        }
    }

    public function suspendUser()
    {
        $this->validate([
            'suspendReason' => 'required|string|max:500',
            'suspendDuration' => 'required|integer|min:1|max:365',
        ]);

        try {
            $this->suspendingUser->update([
                'suspended_at' => now(),
                'suspended_until' => now()->addDays($this->suspendDuration),
                'suspend_reason' => $this->suspendReason,
                'suspended_by' => auth()->id(),
            ]);

            Log::info('User suspended by admin', [
                'suspended_user_id' => $this->suspendingUser->id,
                'suspended_by' => auth()->id(),
                'reason' => $this->suspendReason,
                'duration_days' => $this->suspendDuration,
            ]);

            session()->flash('message', "User berhasil disuspend selama {$this->suspendDuration} hari");
            $this->closeSuspendModal();

        } catch (\Exception $e) {
            Log::error('Failed to suspend user: ' . $e->getMessage());
            session()->flash('error', 'Gagal menangguhkan user: ' . $e->getMessage());
        }
    }

    public function reactivateUser($userId)
    {
        try {
            $user = User::findOrFail($userId);
            $user->update([
                'suspended_at' => null,
                'suspended_until' => null,
                'suspend_reason' => null,
                'suspended_by' => null,
            ]);

            Log::info('User reactivated by admin', [
                'reactivated_user_id' => $userId,
                'reactivated_by' => auth()->id(),
            ]);

            session()->flash('message', 'User berhasil diaktifkan kembali');

        } catch (\Exception $e) {
            Log::error('Failed to reactivate user: ' . $e->getMessage());
            session()->flash('error', 'Gagal mengaktifkan kembali user: ' . $e->getMessage());
        }
    }

    public function deleteUser()
    {
        if ($this->deletingUser) {
            try {
                DB::beginTransaction();

                // Update related registrasi_akses if exists
                RegistrasiAkses::where('user_id', $this->deletingUser->id)->update([
                    'status' => 'deleted',
                    'admin_catatan' => 'User account deleted by admin on ' . now()->format('Y-m-d H:i:s'),
                ]);

                $this->deletingUser->delete();

                DB::commit();

                Log::info('User deleted by admin', [
                    'deleted_user_id' => $this->deletingUser->id,
                    'deleted_by' => auth()->id(),
                ]);

                session()->flash('message', 'User berhasil dihapus.');
                $this->closeDeleteModal();

            } catch (\Exception $e) {
                DB::rollback();
                Log::error('Failed to delete user: ' . $e->getMessage());
                session()->flash('error', 'Gagal menghapus user: ' . $e->getMessage());
            }
        }
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterRole = '';
        $this->filterStatus = '';
        $this->resetPage();
    }

    private function resetForm()
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->selectedRole = '';
        $this->editingUser = null;
        $this->resetErrorBag();
    }

    public function render()
    {
        $perPage = (int) $this->perPage;
        if (! in_array($perPage, $this->perPageOptions, true)) {
            $perPage = 10;
        }

        $query = User::query();

        // Apply search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        // Apply role filter
        if ($this->filterRole) {
            $query->whereHas('roles', function($q) {
                $q->where('name', $this->filterRole);
            });
        }

        // Apply status filter
        if ($this->filterStatus === 'active') {
            $query->whereNull('suspended_at');
        } elseif ($this->filterStatus === 'suspended') {
            $query->whereNotNull('suspended_at')
                  ->where('suspended_until', '>', now());
        }

        $users = $query->with('roles')->paginate($perPage);

        $roles = Role::all();

        // Statistics
        $stats = [
            'total' => User::count(),
            'active' => User::whereNull('suspended_at')->count(),
            'suspended' => User::whereNotNull('suspended_at')
                              ->where('suspended_until', '>', now())
                              ->count(),
            'pemerintah' => User::whereHas('roles', fn($q) => $q->where('name', 'pemerintah'))->count(),
            'akademisi' => User::whereHas('roles', fn($q) => $q->where('name', 'akademisi'))->count(),
        ];

        return view('livewire.admin.user-management', [
            'users' => $users,
            'roles' => $roles,
            'stats' => $stats,
        ]);
    }

    public function export()
    {
        $format = strtolower($this->exportFormat ?? 'xlsx');
        if (! in_array($format, ['xlsx','csv'], true)) {
            $format = 'xlsx';
        }

        $filename = 'users-' . now()->format('Ymd-His') . '.' . $format;

        return Excel::download(new UsersExport($this->search ?: null), $filename, $format === 'csv' ? \Maatwebsite\Excel\Excel::CSV : \Maatwebsite\Excel\Excel::XLSX);
    }

    public function print()
    {
        // Trigger browser print via JS listener
        $this->dispatch('print-users');
    }
}
