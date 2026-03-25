<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contenant extends Model
{
    protected $table = 'contenants';

    const TYPE_CLASSICS = 'classics';
    const TYPE_LUXE = 'luxe';

    protected $fillable = [
        'ml',
        'type_contenant',
        'type',
        'prix',
    ];

    protected $casts = [
        'ml' => 'integer',
        'prix' => 'integer',
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
}
