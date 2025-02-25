<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'username' => 'admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);
        Admin::create([
            'user_id' => $admin->id,
            'fullname' => 'Admin User',
            'phone_number' => 1234567890,
            'provincy_id' => 1,
            'regency_id' => 3,
            'district_id' => 3,
            'full_address' => 'Jl. Sudirman No. 1',
            'profile_img' => 'path/to/image.jpg',
        ]);
    }
}
