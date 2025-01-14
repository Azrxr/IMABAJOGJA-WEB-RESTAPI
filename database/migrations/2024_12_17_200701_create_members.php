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
        Schema::create('members', function (Blueprint $table) {
            $table->id()->unique();
            $table->unsignedBigInteger('user_id')->unique();
            $table->string('no_member')->unique()->nullable();

            $table->string('fullname');
            $table->integer('phone_number');

            $table->string('province')->nullable();
            $table->string('regency')->nullable();
            $table->string('address')->nullable();

            $table->string('agama');
            $table->integer('nisn')->nullable()->comment('opsional');
            $table->string('tempat');
            $table->date('tanggal_lahir');
            $table->enum('gender', ['male', 'female']);
            $table->integer('kode_pos')->nullable();

            $table->enum('member_type', ['camaba', 'pengurus', 'anggota', 'demissioner', 'istimewa'])->default('camaba');
            $table->string('profile_img')->nullable()->comment('path to image');

            $table->string('scholl_origin');
            $table->integer('tahun_lulus');

            $table->string('kampus')->nullable();
            $table->string('fakultas')->nullable();
            $table->string('prodi')->nullable();
            
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
