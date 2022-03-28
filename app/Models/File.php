<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

class File extends AdminModel
{
    use HasFactory, Notifiable;

    protected $table = 'files';

    /**
     * @var string[]
     */
    protected $fillable = [
        'book_id',
        'filename',
        'image',
        'context',
        'extension',
    ];

    /**
     * @return BelongsTo
     */
    public function books(): BelongsTo
    {
        return $this->BelongsTo(Book::class, 'book_id', 'id');
    }
}
