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
        Schema::table('product_categories', function (Blueprint $table) {
            $table->foreignUuid('bisnis_id')->after('id')->constrained('bisnis')->cascadeOnDelete()->after('id');
            $table->string('slug')->unique()->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('product_categories', function (Blueprint $table) {
            $table->dropForeign(['bisnis_id']);
            $table->dropColumn('bisnis_id');
        });
    }
};
