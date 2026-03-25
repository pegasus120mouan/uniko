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
        Schema::create('parfum_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parfum_id')->constrained()->cascadeOnDelete();
            $table->foreignId('contenant_id')->constrained()->cascadeOnDelete();
            $table->integer('prix');
            $table->timestamps();

            $table->unique(['parfum_id', 'contenant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parfum_prices');
    }
};
