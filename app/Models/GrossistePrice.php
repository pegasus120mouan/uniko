<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GrossistePrice extends Model
{
    protected $table = 'grossiste_prices';

    protected $fillable = [
        'grossiste_id',
        'contenant_id',
        'prix',
    ];

    protected $casts = [
        'prix' => 'integer',
    ];

    public function grossiste(): BelongsTo
    {
        return $this->belongsTo(Grossiste::class);
    }

    public function contenant(): BelongsTo
    {
        return $this->belongsTo(Contenant::class);
    }
}
