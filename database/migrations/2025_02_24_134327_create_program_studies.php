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
        Schema::create('program_studies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('university_id');
            $table->unsignedBigInteger('faculty_id')->nullable();
            $table->string('name');
            $table->enum('jenjang',['D1','D2','D3','D4','S1','S2','S3','Profesi','S2 Terapan']);
            $table->foreign('university_id')->references('id')->on('universities');
            $table->foreign('faculty_id')->references('id')->on('faculties');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_studies');
    }
};
