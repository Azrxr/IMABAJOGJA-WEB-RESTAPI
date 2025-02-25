<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->morphs('tokenable');
            $table->string('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });

        DB::table('personal_access_tokens')->insert([
            'id' => 5,
            'tokenable_type' => 'App\\Models\\User', // Sesuaikan dengan model
            'tokenable_id' => 1, // ID user yang ingin diberi token (pastikan user dengan ID ini ada)
            'name' => 'Test Token admin',
            'token' => 'A3s0R7lA0dMxqYMoLq1qoZtmaRBISxOkSw5Zf494', // Hash token agar sesuai dengan Laravel Sanctum
            'abilities' => json_encode(['*']), // Akses penuh
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('personal_access_tokens')->insert([
            'id' => 4,
            'tokenable_type' => 'App\\Models\\User', // Sesuaikan dengan model
            'tokenable_id' => 2, // ID user yang ingin diberi token (pastikan user dengan ID ini ada)
            'name' => 'Test Token member',
            'token' => 'qPM4Ut0rcRSMUnzo9Bb38fSk8AH3RsXAa0HjjBcP', // Hash token agar sesuai dengan Laravel Sanctum
            'abilities' => json_encode(['*']), // Akses penuh
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_access_tokens');
    }
};
