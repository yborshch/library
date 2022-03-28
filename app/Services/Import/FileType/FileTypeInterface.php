<?php

namespace App\Services\Import\FileType;

use App\ValueObject\Book;

interface FileTypeInterface
{
    /**
     * @param Book $book
     * @return bool
     */
    public function save(Book &$book): bool;
}
