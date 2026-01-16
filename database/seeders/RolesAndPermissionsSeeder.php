<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Roles
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'supervisor']);
        Role::firstOrCreate(['name' => 'collector']);
        Role::firstOrCreate(['name' => 'user']);
    }
}
