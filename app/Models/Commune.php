<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commune extends Model
{
    use HasFactory;

    protected $table = 'communes';

    protected $fillable = [
        'nom',
        'cout_livraison',
    ];

    protected function casts(): array
    {
        return [
            'cout_livraison' => 'integer',
        ];
    }
}
