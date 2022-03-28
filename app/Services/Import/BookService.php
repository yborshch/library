<?php

namespace App\Services\Import;


use App\Services\Import\FileType\FileTypeInterface;
use App\Services\Import\Parser\ParserInterface;
use App\ValueObject\Book;

class BookService implements BookServiceInterface
{

    /**
     * @param ParserInterface $parser
     * @return Book
     */
    public function createBook(ParserInterface $parser): Book
    {
        $book = $parser->getBookInformation();
        $book->context = $parser->getBookContext($book);

        return $book;
    }

    /**
     * @param FileTypeInterface $fileType
     * @param Book $book
     * @return bool
     */
    public function saveTo(FileTypeInterface $fileType, Book $book): bool
    {
        return $fileType->save($book);
    }
}
