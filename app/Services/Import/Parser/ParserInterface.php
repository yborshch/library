<?php

namespace App\Services\Import\Parser;


use App\ValueObject\Book;

interface ParserInterface
{
    /**
     * @param string $url
     * @return void
     */
    public function setUrlToBookInformation(string $url): void;

    /**
     * @return Book
     */
    public function getBookInformation(): Book;

    /**
     * @param Book $bookInfo
     * @return mixed
     */
    public function getBookContext(Book $bookInfo): mixed;
}
