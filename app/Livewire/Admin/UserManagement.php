<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;

class UserManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public array $perPageOptions = [5, 10, 25, 100];
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    
    public $name = '';
    public $email = '';
    public $password = '';
    public $selectedRole = '';
    public $editingUser = null;
    public $deletingUser = null;
    public $exportFormat = 'xlsx';

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
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

    public function createUser()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password),
        ]);

        if ($this->selectedRole) {
            $user->assignRole($this->selectedRole);
        }

        session()->flash('message', 'User berhasil dibuat.');
        $this->closeCreateModal();
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

        $this->editingUser->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        if (!empty($this->password)) {
            $this->editingUser->update(['password' => bcrypt($this->password)]);
        }

        $this->editingUser->syncRoles([$this->selectedRole]);

        session()->flash('message', 'User berhasil diupdate.');
        $this->closeEditModal();
    }

    public function deleteUser()
    {
        if ($this->deletingUser) {
            $this->deletingUser->delete();
            session()->flash('message', 'User berhasil dihapus.');
            $this->closeDeleteModal();
        }
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

        $users = User::when($this->search, function ($query) {
                $query->where(function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                });
        })->with('roles')->paginate($perPage);

        $roles = Role::all();

        return view('livewire.admin.user-management', [
            'users' => $users,
            'roles' => $roles,
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
