<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PerguruanTinggiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sqlFile = database_path('sql/perguruan_tinggi_jogja.sql'); // Lokasi file
        $sql = File::get($sqlFile); // Baca file SQL

        DB::unprepared($sql);
    }
}
