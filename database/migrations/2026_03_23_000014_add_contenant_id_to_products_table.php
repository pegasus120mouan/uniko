<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('contenant_id')->nullable()->constrained('contenants')->nullOnDelete()->after('parfum_id');
            $table->index('contenant_id');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['contenant_id']);
            $table->dropConstrainedForeignId('contenant_id');
        });
    }
};
