<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed $bookmark
 */
class Context extends Model
{
    use HasFactory;

    protected $table = 'context';

    public $timestamps = true;

    /**
     * @var string[]
     */
    protected $fillable = [
        'book_id',
        'page',
        'text',
        'bookmark'
    ];

    protected $casts = [
        'bookmark' => 'array',
    ];
}
