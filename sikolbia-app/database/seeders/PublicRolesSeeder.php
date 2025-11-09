<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PublicRolesSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create roles for public users
        $pemerintahRole = Role::firstOrCreate(['name' => 'pemerintah']);
        $akademisiRole = Role::firstOrCreate(['name' => 'akademisi']);
        
        // Define permissions for each role
        $publicPermissions = [
            // Ketersediaan Pangan - Read only
            'ketersediaan.view',
            'ketersediaan.export',
            
            // Lahan - Read only
            'lahan.view',
            'lahan.export',
            
            // Benih Pupuk - Read only  
            'benih-pupuk.view',
            'benih-pupuk.export',
            
            // Iklim OptDPI - Read only
            'iklim-opt-dpi.view',
            'iklim-opt-dpi.export',
            
            // NBM Prediction - View only
            'nbm.view',
            'nbm.predict',
        ];
        
        // Create permissions if not exist
        foreach ($publicPermissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
        }
        
        // Assign permissions to pemerintah role
        $pemerintahRole->givePermissionTo($publicPermissions);
        
        // Assign permissions to akademisi role  
        $akademisiRole->givePermissionTo($publicPermissions);
        
        $this->command->info('Public roles (pemerintah & akademisi) created successfully with read permissions!');
    }
}
