<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();

            $table->string('full_name');
            $table->string('phone', 30);

            $table->string('delivery_mode');
            $table->foreignId('commune_id')->nullable()->constrained('communes')->nullOnDelete();
            $table->string('commune_nom')->nullable();

            $table->text('address')->nullable();
            $table->text('note')->nullable();

            $table->unsignedInteger('subtotal')->default(0);
            $table->unsignedInteger('cout_livraison')->default(0);
            $table->unsignedInteger('montant_a_payer')->default(0);

            $table->string('status')->default('pending_confirmation');
            $table->timestamp('confirmed_at')->nullable();

            $table->timestamps();

            $table->index(['status']);
            $table->index(['created_at']);
            $table->index(['phone']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
