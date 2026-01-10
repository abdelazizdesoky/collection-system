<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;


class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
  {
        // إنشاء Roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $collectorRole = Role::firstOrCreate(['name' => 'collector']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // إنشاء Users
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('123456789')
        ]);
        $admin->assignRole(['admin', 'collector']); // أكثر من دور

        $collector = User::create([
            'name' => 'Collector',
            'email' => 'collector@admin.com',
            'password' => bcrypt('123456789')
        ]);
        $collector->assignRole('collector');

        $normalUser = User::create([
            'name' => 'User',
            'email' => 'user@admin.com',
            'password' => bcrypt('123456789')
        ]);
        $normalUser->assignRole('user');
    }
}