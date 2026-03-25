<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Parfum extends Model
{
    protected $table = 'parfums';

    const TYPE_CLASSICS = 'classics';
    const TYPE_LUXE = 'luxe';

    protected $fillable = [
        'code',
        'nom',
        'type',
    ];

    public static function getTypes(): array
    {
        return [
            self::TYPE_CLASSICS => 'Classics',
            self::TYPE_LUXE => 'Luxe',
        ];
    }

    public function isLuxe(): bool
    {
        return $this->type === self::TYPE_LUXE;
    }

    public function isClassics(): bool
    {
        return $this->type === self::TYPE_CLASSICS;
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function prices(): HasMany
    {
        return $this->hasMany(ParfumPrice::class);
    }

    public function contenants()
    {
        return $this->belongsToMany(Contenant::class, 'parfum_prices')
            ->withPivot('prix')
            ->withTimestamps();
    }
}
