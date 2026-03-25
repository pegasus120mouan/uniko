<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParfumPrice extends Model
{
    protected $table = 'parfum_prices';

    protected $fillable = [
        'parfum_id',
        'contenant_id',
        'prix',
    ];

    protected $casts = [
        'prix' => 'integer',
    ];

    public function parfum(): BelongsTo
    {
        return $this->belongsTo(Parfum::class);
    }

    public function contenant(): BelongsTo
    {
        return $this->belongsTo(Contenant::class);
    }
}
