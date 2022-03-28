<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

/**
 * @method static find(mixed $id)
 * @method static create(array $store)
 * @method static orderBy(string $string, mixed $orderBy)
 */
class Series extends AdminModel
{
    use HasFactory, Notifiable;

    protected $table = 'series';

    /**
     * @var string[]
     */
    protected $fillable = [
        'title',
    ];
}
