<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'superadmin',
                'display_name' => 'Super Admin',
                'description' => 'Super Administrator dengan akses penuh ke semua fitur sistem',
            ],
            [
                'name' => 'admin',
                'display_name' => 'Admin',
                'description' => 'Administrator dengan akses terbatas ke fitur sistem',
            ],
            [
                'name' => 'penjual',
                'display_name' => 'Penjual/Outlet',
                'description' => 'Pemilik outlet yang dapat mengelola outlet dan menu',
            ],
            [
                'name' => 'pembeli',
                'display_name' => 'Pembeli',
                'description' => 'Pengguna yang dapat memesan dan memberikan rating',
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
