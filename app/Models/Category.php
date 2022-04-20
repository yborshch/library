<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

/**
 * @method static create(array $category)
 * @method static orderBy(string $string, mixed $orderBy)
 * @method static validateOrder(mixed $orderBy)
 * @method static find(\Illuminate\Support\HigherOrderCollectionProxy|mixed $id)
 */
class Category extends AdminModel
{
    use HasFactory, Notifiable;

    protected $table = 'categories';

    /**
     * @var string[]
     */
    protected $fillable = [
        'title',
    ];

    /**
     * @return HasMany
     */
    public function books(): HasMany
    {
        return $this
            ->hasMany(Book::class, 'category_id', 'id');
    }
}
