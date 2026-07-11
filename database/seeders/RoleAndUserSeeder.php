<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class RoleAndUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin']);
        $programAdminRole = Role::firstOrCreate(['name' => 'program-admin']);
        $editorRole = Role::firstOrCreate(['name' => 'editor']);
        $contributorRole = Role::firstOrCreate(['name' => 'contributor']);

        // Create default Super Admin User
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@ppms.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
            ]
        );

        // Assign super-admin role
        $superAdmin->assignRole($superAdminRole);
    }
}
