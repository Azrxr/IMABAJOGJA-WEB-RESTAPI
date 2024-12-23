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
        Schema::create('studies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id')->unique();
            $table->foreign('member_id')->references('id')->on('members');
            $table->string('perguruan_tinggi_name');
            $table->string('fakultas');
            $table->string('program_study');
            $table->integer('tahun_masuk')->nullable()->comment('sudah kuliah');
            $table->text('rencana_masuk')->nullable()->comment('calon');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('studys');
    }
};
