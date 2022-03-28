<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;

/**
 * @method static create(array $author)
 * @method static paginate(int $perPage)
 * @method static orderBy(string $string, string $order)
 * @method static find(mixed $id)
 * @method static where(string $string, string $string1, string $string2)
 */
class Author extends AdminModel
{
    use HasFactory, Notifiable;

    protected $table = 'authors';

    /**
     * @var string[]
     */
    protected $fillable = [
        'firstname',
        'lastname',
    ];

    /**
     * @return BelongsToMany
     */
    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'author_book')->withTimestamps();
    }

    /**
     * @return string
     */
    public function getFullAuthorName(): string
    {
        return "{$this->firstname} {$this->lastname}";
    }
}
