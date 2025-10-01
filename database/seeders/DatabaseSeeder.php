<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Jalankan seeder role dan position terlebih dahulu
        $this->call([
            RoleSeeder::class,
            PositionSeeder::class,
        ]);

        // Buat user ADMIN dengan semua permission
        $admin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('admin123'),
        ]);
        $admin->assignRole('admin');

        // Buat user BIASA tanpa permission (role: user)
        $regularUser = User::factory()->create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => bcrypt('user123'),
        ]);
        $regularUser->assignRole('user');

        // Buat user STAFF tanpa permission (akan diatur admin nanti)
        $staff = User::factory()->create([
            'name' => 'Staff User',
            'email' => 'staff@example.com',
            'password' => bcrypt('staff123'),
        ]);
        $staff->assignRole('staff');

        // Buat user MANAGER tanpa permission (akan diatur admin nanti)
        $manager = User::factory()->create([
            'name' => 'Manager User',
            'email' => 'manager@example.com',
            'password' => bcrypt('manager123'),
        ]);
        $manager->assignRole('manager');

        // Output info login
        $this->command->info('=== AKUN DEFAULT BERHASIL DIBUAT ===');
        $this->command->info('');
        $this->command->info('ADMIN (Full Permission):');
        $this->command->info('Email: admin@example.com');
        $this->command->info('Password: admin123');
        $this->command->info('Permission: create, update, delete, view, admin access');
        $this->command->info('');
        $this->command->info('USER BIASA (Tanpa Permission):');
        $this->command->info('Email: user@example.com');
        $this->command->info('Password: user123');
        $this->command->info('Permission: Kosong (akan diatur admin)');
        $this->command->info('');
        $this->command->info('STAFF (Tanpa Permission):');
        $this->command->info('Email: staff@example.com');
        $this->command->info('Password: staff123');
        $this->command->info('Permission: Kosong (akan diatur admin)');
        $this->command->info('');
        $this->command->info('MANAGER (Tanpa Permission):');
        $this->command->info('Email: manager@example.com');
        $this->command->info('Password: manager123');
        $this->command->info('Permission: Kosong (akan diatur admin)');
    }
}