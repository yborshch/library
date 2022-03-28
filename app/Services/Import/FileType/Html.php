<?php

namespace App\Services\Import\FileType;

use App\ValueObject\Book;

class Html implements FileTypeInterface
{
    /**
     * @param Book $book
     * @return bool
     */
    public function save(Book &$book): bool
    {
        return true;
    }
}
