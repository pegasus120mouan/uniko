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
        // Add type column to parfums table
        Schema::table('parfums', function (Blueprint $table) {
            $table->enum('type', ['classics', 'luxe'])->default('classics')->after('nom');
            $table->index('type');
        });

        // Remove type column from products table
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['type']);
            $table->dropColumn('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-add type column to products table
        Schema::table('products', function (Blueprint $table) {
            $table->enum('type', ['classics', 'luxe'])->default('classics')->after('category_id');
            $table->index('type');
        });

        // Remove type column from parfums table
        Schema::table('parfums', function (Blueprint $table) {
            $table->dropIndex(['type']);
            $table->dropColumn('type');
        });
    }
};
