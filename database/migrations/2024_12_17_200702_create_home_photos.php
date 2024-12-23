<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('home_photos', function (Blueprint $table) {
            $table->id();
            $table->string('photo_title')->nullable()->comment('judul bagian rumah');
            $table->string('photo_img')->comment('path to img');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_photos');
    }
};
