<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Admin;
use App\Models\Study;
use App\Models\Member;
use App\Models\Document;
use App\Models\HomePhoto;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

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
            'province' => 'DKI Jakarta',
            'regency' => 'Jakarta Selatan',
            'district' => 'Kebayoran Baru',
            'full_address' => 'Jl. Sudirman No. 1',
            'profile_img' => 'path/to/image.jpg',
        ]);
        $member = User::create([
            'username' => 'member',
            'email' => 'member@example.com',
            'role' => 'member',
            'password' => Hash::make('password'),
        ]);
        $member_data = Member::create([
            'user_id' => $member->id,
            'fullname' => 'Member User',
            'phone_number' => 1234567890,
            'province' => 'DI Yogyakarta',
            'regency' => 'Yogyakarta',
            'district' => 'Gondokusuman',
            'full_address' => 'Jl. Malioboro No. 1',
            'profile_img' => 'path/to/image.jpg',
        ]);

        $home = HomePhoto::create([
            'photo_title' => 'Home Photo',
            'photo_img' => 'path/to/photo.jpg',
        ]);
        Document::create([
            'member_id' => $member_data->id,
            'ijazah' => 'path/to/ijazah.jpg',
            'home_photo_id' => $home->id
        ]);

        Study::create([
            'member_id' => $member_data->id,
            'perguruan_tinggi_name' => 'Universitas Gadjah Mada',
            'fakultas' => 'Teknik',
            'program_study' => 'Informatika',
            'rencana_masuk' => 2024,
        ]);
        $banned = User::create([
            'username' => 'banned',
            'email' => 'banned@example.com',
            'role' => 'member',
            'banned' => true,
            'ban_reason' => 'spam',
            'password' => Hash::make('password'),
        ]);
        Member::create([
            'user_id' => $banned->id,
            'fullname' => 'Banned User',
            'phone_number' => 1234567890,
            'province' => 'Jawa Tengah',
            'regency' => 'Semarang',
            'district' => 'Candisari',
            'full_address' => 'Jl. Pandanaran No. 1',
            'profile_img' => 'path/to/image.jpg',
        ]);
    }
}
