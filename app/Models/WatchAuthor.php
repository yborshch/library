<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

/**
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
    public function books(): HasMany
    {
        return $this->hasMany(WatchBook::class, 'author_id');
    }
}
