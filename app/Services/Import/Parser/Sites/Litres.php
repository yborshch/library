<?php

namespace App\Services\Import\Parser\Sites;

use App\Services\Book\Builder\Classes\Book;
use App\Services\Import\Parser\ParserInterface;

class Litres implements ParserInterface
{

    public function setUrlToBookInformation(string $url): void
    {
        //
    }

    public function getBookInformation(): Book
    {
        return new Book();
    }

    public function getBookContext(Book $bookInfo): mixed
    {
        return '';
    }
}
