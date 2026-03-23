<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'order_number',
        'full_name',
        'phone',
        'delivery_mode',
        'commune_id',
        'commune_nom',
        'address',
        'note',
        'subtotal',
        'cout_livraison',
        'montant_a_payer',
        'status',
        'confirmed_at',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'integer',
            'cout_livraison' => 'integer',
            'montant_a_payer' => 'integer',
            'confirmed_at' => 'datetime',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function commune(): BelongsTo
    {
        return $this->belongsTo(Commune::class, 'commune_id');
    }
}
