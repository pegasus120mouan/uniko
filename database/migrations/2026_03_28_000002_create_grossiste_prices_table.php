<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grossiste_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grossiste_id')->constrained()->cascadeOnDelete();
            $table->foreignId('contenant_id')->constrained()->cascadeOnDelete();
            $table->integer('prix');
            $table->timestamps();

            $table->unique(['grossiste_id', 'contenant_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grossiste_prices');
    }
};
