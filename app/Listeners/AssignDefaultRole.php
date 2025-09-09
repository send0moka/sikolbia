<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AssignDefaultRole
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        // Ensure default 'admin' role exists (tests may run before seeder)
        if (! Role::where('name', 'admin')->exists()) {
            // Guarantee base permission exists
            $permission = Permission::firstOrCreate(['name' => 'view dashboard']);
            $role = Role::firstOrCreate(['name' => 'admin']);
            $role->givePermissionTo($permission);
        }

        // Assign default role to new user (ignore if already has one)
        if (! $event->user->hasRole('admin')) {
            $event->user->assignRole('admin');
        }
    }
}
