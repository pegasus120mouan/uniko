<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;

class Parfum extends Model
{
    protected $table = 'parfums';

    protected $fillable = [
        'code',
        'nom',
    ];

    public function product(): HasOne
    {
        return $this->hasOne(Product::class);
    }
}
