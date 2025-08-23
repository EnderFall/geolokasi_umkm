<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@geolokasi.com',
            'phone' => '081234567890',
            'address' => 'Jakarta, Indonesia',
            'role' => 'superadmin',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);

        // Create Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@geolokasi.com',
            'phone' => '081234567891',
            'address' => 'Bandung, Indonesia',
            'role' => 'admin',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);

        // Create Sample Penjual
        User::create([
            'name' => 'Pak Joko',
            'email' => 'joko@outlet.com',
            'phone' => '081234567892',
            'address' => 'Surabaya, Indonesia',
            'role' => 'penjual',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);

        // Create Sample Pembeli
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@pembeli.com',
            'phone' => '081234567893',
            'address' => 'Yogyakarta, Indonesia',
            'role' => 'pembeli',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);
    }
}
