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
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            // $table->foreignUuid('bisnis_id')->constrained('bisnis')->onDelete('cascade')->nullable();
            // $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade')->nullable();
            $table->foreignUuid('bisnis_id')->nullable();
            $table->foreign('bisnis_id')->references('id')->on('bisnis')->onDelete('cascade');
            $table->foreignUuid('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('invoice_number')->unique();
            $table->string('cashier_name')->nullable()->default('staff');
            $table->string('customer_name')->nullable();
            $table->string('total_amount');
            $table->string('payment_method')->nullable();
            $table->string('status')->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
