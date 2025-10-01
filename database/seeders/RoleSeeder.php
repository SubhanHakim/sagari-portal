<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['admin', 'staff', 'user', 'manager', 'supervisor'];
        
        $permissions = [
            'create',
            'update', 
            'delete',
            'view',
            'admin access',
        ];

        // Buat semua permission
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        // Buat semua role
        foreach ($roles as $roleName) {
            $role = Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web',
            ]);

            // HANYA ADMIN yang langsung dapat semua permission
            if ($roleName === 'admin') {
                $role->syncPermissions($permissions);
            }
            
            // Role lain dibiarkan kosong (akan diatur oleh admin nanti via web)
        }

        // Output info setelah seeding
        $this->command->info('Permissions berhasil dibuat: create, update, delete, view, admin access');
        $this->command->info('- Admin: Mendapat SEMUA permission (siap pakai)');
        $this->command->info('- Role lain: Dibuat kosong, admin bisa assign permission via web');
    }
}