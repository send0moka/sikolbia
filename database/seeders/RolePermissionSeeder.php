<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles/permissions for consistent seeding
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        // Define permissions (superadmin will get all; admin intentionally limited)
        $permissions = [
            'manage users', // full user management
            'view users',   // list users
            'create users',
            'edit users',
            'delete users',
            'manage roles',
            'view dashboard',
            'manage kelompok', // kelompok management
            'view kelompok',
            'create kelompok',
            'edit kelompok',
            'delete kelompok',
            'export kelompok',
            'manage komoditi', // komoditi management
            'view komoditi',
            'create komoditi',
            'edit komoditi',
            'delete komoditi',
            'export komoditi',
            'manage transaksi_nbm', // transaksi NBM management
            'view transaksi_nbm',
            'create transaksi_nbm',
            'edit transaksi_nbm',
            'delete transaksi_nbm',
            'export transaksi_nbm',
            
            // Susenas permissions
            'manage susenas',
            'view susenas',
            'create susenas',
            'edit susenas',
            'delete susenas',
            
            // Kelompok BPS permissions
            'manage kelompokbps',
            'view kelompokbps',
            'create kelompokbps',
            'edit kelompokbps',
            'delete kelompokbps',
            
            // Komoditi BPS permissions
            'manage komoditibps',
            'view komoditibps',
            'create komoditibps',
            'edit komoditibps',
            'delete komoditibps',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create / fetch roles
        $superadminRole = Role::firstOrCreate(['name' => 'superadmin']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Assign permissions idempotently
        $superadminRole->syncPermissions(Permission::all());

        // admin can manage all except user management
        $adminRole->syncPermissions([
            'view dashboard',
            'manage kelompok', // kelompok management
            'view kelompok',
            'create kelompok',
            'edit kelompok',
            'delete kelompok',
            'export kelompok',
            'manage komoditi', // komoditi management
            'view komoditi',
            'create komoditi',
            'edit komoditi',
            'delete komoditi',
            'export komoditi',
            'manage transaksi_nbm', // transaksi NBM management
            'view transaksi_nbm',
            'create transaksi_nbm',
            'edit transaksi_nbm',
            'delete transaksi_nbm',
            'export transaksi_nbm',
            
            // Susenas permissions
            'manage susenas',
            'view susenas',
            'create susenas',
            'edit susenas',
            'delete susenas',
            
            // Kelompok BPS permissions
            'manage kelompokbps',
            'view kelompokbps',
            'create kelompokbps',
            'edit kelompokbps',
            'delete kelompokbps',
            
            // Komoditi BPS permissions
            'manage komoditibps',
            'view komoditibps',
            'create komoditibps',
            'edit komoditibps',
            'delete komoditibps',
        ]);

        // Create a superadmin user if it doesn't exist
        $superadmin = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'),
            ]
        );
        if (! $superadmin->hasRole('superadmin')) {
            $superadmin->assignRole('superadmin');
        }

        // Create an admin user if it doesn't exist
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password'),
            ]
        );
        if (! $admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }
    }
}
