<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;

/**
 * @property File $images
 */
class Book extends AdminModel
{
    use HasFactory, Notifiable;

    /**
     * @var string
     */
    protected $table = 'books';

    /**
     * @var string[]
     */
    protected $fillable = [
        'category_id',
        'series_id',
        'current_page',
        'description',
        'images',
        'pages',
        'readed',
        'source',
        'source_link',
        'title',
        'type',
        'year',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'readed' => 'boolean',
    ];

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this
            ->belongsTo(Category::class);
    }

    /**
     * @return BelongsToMany
     */
    public function authors(): BelongsToMany
    {
        return $this
            ->belongsToMany(Author::class, 'author_book')
            ->withTimestamps();
    }

    /**
     * @return BelongsTo
     */
    public function series(): BelongsTo
    {
        return $this
            ->belongsTo(Series::class);
    }

    /**
     * @return BelongsToMany
     */
    public function tags(): BelongsToMany
    {
        return $this
            ->belongsToMany(Tag::class, 'book_tag')
            ->withTimestamps();
    }

    /**
     * @return HasOne
     */
    public function queue(): HasOne
    {
        return $this
            ->hasOne(Queue::class);
    }

    /**
     * @return HasOne
     */
    public function image(): HasOne
    {
        return $this
            ->hasOne(File::class)
            ->where('image', true);
    }

    /**
     * @return HasMany
     */
    public function files(): HasMany
    {
        return $this
            ->hasMany(File::class, 'book_id', 'id')
            ->where('image', true);
    }
}
