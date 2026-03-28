<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Grossiste extends Model
{
    protected $table = 'grossistes';

    protected $fillable = [
        'nom',
        'entreprise',
        'telephone',
        'email',
        'adresse',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function prices(): HasMany
    {
        return $this->hasMany(GrossistePrice::class);
    }
}
