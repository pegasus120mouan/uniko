<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contenants', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('ml');
            $table->string('type_contenant', 100);
            $table->unsignedInteger('prix');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contenants');
    }
};
