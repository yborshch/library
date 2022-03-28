<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class WatchBook extends AdminModel
{
    use HasFactory, Notifiable;

    protected $table = 'watch_books';

    /**
     * @var string[]
     */
    protected $fillable = [
        'author_id',
        'book_id',
        'series_id',
        'image',
        'title',
        'url',
    ];
}
