<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contenant extends Model
{
    protected $table = 'contenants';

    protected $fillable = [
        'ml',
        'type_contenant',
        'prix',
    ];

    protected $casts = [
        'ml' => 'integer',
        'prix' => 'integer',
    ];
}
