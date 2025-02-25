<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class WilayahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $sqlFile = database_path('sql/wilayah18insert.sql'); // Lokasi file
        $sql = File::get($sqlFile); // Baca file SQL

        DB::unprepared($sql);
    }
}
