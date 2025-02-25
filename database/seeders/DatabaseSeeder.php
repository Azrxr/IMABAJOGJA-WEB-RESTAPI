<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Admin;
use App\Models\Study;
use App\Models\Member;
use App\Models\Campuse;
use App\Models\Document;
use App\Models\HomePhoto;
use App\Models\ProgramStudy;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\InsertWilayahSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        $this->call([
            WilayahSeeder::class,
            AdminSeeder::class,
            MemberSeeder::class,
        ]);
    
        
    }
}
