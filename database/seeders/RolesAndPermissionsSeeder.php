<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arrayOfPermissionNames = [
            'update',
            'delete',
            'store'
        ];
        foreach ($arrayOfPermissionNames as $permissionName) {
            // Check if permission exists before creating
            Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'api']);
        }

        // Create or get the super_admin role and assign permissions
        $role = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'api']);
        $role->syncPermissions($arrayOfPermissionNames);
    }
}
