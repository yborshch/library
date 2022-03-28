<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class WatchSeries extends AdminModel
{
    use HasFactory, Notifiable;

    protected $table = 'watch_series';

    /**
     * @var string[]
     */
    protected $fillable = [
        'author_id',
        'series_id',
        'title',
        'url',
    ];
}
