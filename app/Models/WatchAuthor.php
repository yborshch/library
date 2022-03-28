<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
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
        'url',
        'active'
    ];
}
