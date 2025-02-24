<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\File;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ImportWilayahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $sqlFile = database_path('sql/wilayah18insert.sql'); // Lokasi file
        $sql = File::get($sqlFile); // Baca file SQL

        DB::unprepared($sql);
    }
}
