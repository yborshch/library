<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

/**
 * @method static chunk(int $int, \Closure $param)
 * @method static where(string $string, bool $true)
 * @method static count()
 * @property mixed $id
 * @property mixed $firstname
 * @property mixed $lastname
 */
class WatchAuthor extends AdminModel
{
    use HasFactory, Notifiable;

    protected $table = 'watch_authors';

    /**
     * @var string[]
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'source',
        'url',
        'active'
    ];

    /**
     * @return HasMany
     */
    public function watchBook(): HasMany
    {
        return $this->hasMany(WatchBook::class, 'book_id', 'id');
    }
}
