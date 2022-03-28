<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    /**
     * Table name
     *
     * @var string
     */
    protected  $table = 'messages';

    /**
     * Columns for update
     *
     * @var array
     */
    protected $fillable = [
        'book_id',
        'book',
        'action',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'book' => 'array',
    ];
}
