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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('user_type', ['free', 'pro'])->default('free');
            $table->string('full_name');
            $table->string('address')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('avatar')->nullable();
            $table->string('no_ktp')->nullable();
            $table->string('no_npwp')->nullable();
            $table->string('gender')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->string('religion')->nullable();
            $table->string('status')->default('active');
            $table->integer('quota_ai')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
