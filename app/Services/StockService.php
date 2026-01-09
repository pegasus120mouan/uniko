<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockService
{
    public function adjust(
        Product $product,
        int $quantityChange,
        string $type,
        ?User $user = null,
        ?string $reference = null,
        ?string $comment = null,
        ?\DateTimeInterface $movedAt = null
    ): StockMovement {
        if ($quantityChange === 0) {
            throw ValidationException::withMessages([
                'quantity_change' => 'La quantité doit être différente de 0.',
            ]);
        }

        return DB::transaction(function () use ($product, $quantityChange, $type, $user, $reference, $comment, $movedAt) {
            /** @var Product $locked */
            $locked = Product::query()->lockForUpdate()->findOrFail($product->id);

            $newQuantity = $locked->quantity + $quantityChange;
            if ($newQuantity < 0) {
                throw ValidationException::withMessages([
                    'quantity_change' => 'Stock insuffisant pour effectuer cette opération.',
                ]);
            }

            $locked->update([
                'quantity' => $newQuantity,
            ]);

            return StockMovement::create([
                'product_id' => $locked->id,
                'user_id' => $user?->id,
                'quantity_change' => $quantityChange,
                'type' => $type,
                'reference' => $reference,
                'comment' => $comment,
                'moved_at' => $movedAt ?? now(),
            ]);
        });
    }
}
