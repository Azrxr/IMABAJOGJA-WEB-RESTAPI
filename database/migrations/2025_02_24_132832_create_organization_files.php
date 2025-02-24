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
        Schema::create('organization_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_profile_id')->unique();
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('file_path')->notNullable();
            $table->foreign('organization_profile_id')->references('id')->on('organization_profiles');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_files');
    }
};
