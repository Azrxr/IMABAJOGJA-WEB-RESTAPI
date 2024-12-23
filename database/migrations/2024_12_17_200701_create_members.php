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
            $table->string('fullname');
            $table->integer('phone_number');
            $table->string('province')->nullable();
            $table->string('regency')->nullable();
            $table->string('district')->nullable();
            $table->string('full_address')->nullable();
            $table->string('profile_img')->nullable()->comment('path to image');
            $table->enum('member_status', ['aktif', 'nonaktif', 'pending'])->default('pending');
            $table->enum('approval', ['disetujui', 'ditolak', 'pending'])->default('pending');
            $table->enum('member_type', ['new_member', 'camaba', 'pengurus', 'anggota', 'demissioner'])->default('new_member');
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
