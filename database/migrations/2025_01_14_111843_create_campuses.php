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
        //
        Schema::create('campuses', function (Blueprint $table) {
            $table->id()->unique();
            $table->unsignedBigInteger('member_id');
            $table->string('campuse_name');
            $table->enum('beasiswa', ['sangat_butuh', 'netral', 'berbayar']);
            $table->foreign('member_id')->references('id')->on('members');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
