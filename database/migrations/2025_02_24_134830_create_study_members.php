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
        Schema::create('study_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id');
            $table->unsignedBigInteger('university_id');
            $table->unsignedBigInteger('faculty_id')->nullable();
            $table->unsignedBigInteger('program_study_id');
            $table->foreign('member_id')->references('id')->on('members');
            $table->foreign('university_id')->references('id')->on('universities');
            $table->foreign('faculty_id')->references('id')->on('faculties');
            $table->foreign('program_study_id')->references('id')->on('program_studies');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('study_members');
    }
};
