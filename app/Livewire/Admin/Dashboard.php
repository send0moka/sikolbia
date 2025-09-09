<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class Dashboard extends Component
{
    public function render()
    {
        $totalUsers = User::count();
        $totalRoles = Role::count();
        $recentUsers = User::latest()->take(5)->get();
        
        return view('livewire.admin.dashboard', [
            'totalUsers' => $totalUsers,
            'totalRoles' => $totalRoles,
            'recentUsers' => $recentUsers,
        ])->layout('components.layouts.admin');
    }
}
