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
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->string('no_member')->unique()->nullable();
            $table->string('angkatan')->nullable();
            $table->string('fullname');
            $table->integer('phone_number');

            $table->unsignedBigInteger('province_id')->nullable();
            $table->unsignedBigInteger('regency_id')->nullable();
            $table->unsignedBigInteger('district_id')->nullable();
            $table->text('full_address')->nullable();

            $table->enum('agama', ['islam', 'kristen', 'katolik', 'hindu', 'budha', 'konghucu', 'lainnya'])->nullable();
            $table->integer('nisn')->nullable()->comment('opsional');
            $table->string('tempat');
            $table->date('tanggal_lahir');
            $table->enum('gender', ['male', 'female']);
            $table->integer('kode_pos')->nullable();

            $table->enum('member_type', ['camaba', 'pengurus', 'anggota', 'demissioner', 'istimewa'])->default('camaba');
            $table->string('profile_img_path')->nullable()->comment('path to image');

            $table->string('scholl_origin')->nullable();
            $table->integer('tahun_lulus')->nullable();
            $table->boolean('is_studyng')->comment('TRUE jika sudah kuliah, FALSE jika masih memilih');

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('province_id')->references('id')->on('provincies')->onDelete('set null');
            $table->foreign('regency_id')->references('id')->on('regencies')->onDelete('set null');
            $table->foreign('district_id')->references('id')->on('districts')->onDelete('set null');
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
