<?php

namespace App\Services\Book\Builder;

use App\ValueObject\Book;
use App\Services\Book\Builder\Interfaces\BookBuilderInterface;

class BookBuilder implements BookBuilderInterface
{
    /**
     * @var Book
     */
    private Book $book;

    public function __construct()
    {
        $this->create();
    }

    /**
     * @return BookBuilderInterface
     */
    public function create(): BookBuilderInterface
    {
        $this->book = new Book();

        return $this;
    }

    /**
     * @param string $title
     * @return BookBuilderInterface
     */
    public function setTitle(string $title): BookBuilderInterface
    {
        $this->book->title = $title;

        return $this;
    }

    /**
     * @param string $author
     * @return BookBuilderInterface
     */
    public function setAuthor(string $author): BookBuilderInterface
    {
        array_push($this->book->author, $author);

        return $this;
    }

    /**
     * @param string $description
     * @return BookBuilderInterface
     */
    public function setDescription(string $description): BookBuilderInterface
    {
        $this->book->description = $description;

        return $this;
    }

    /**
     * @param int $pages
     * @return BookBuilderInterface
     */
    public function setPages(int $pages): BookBuilderInterface
    {
        $this->book->pages = $pages;

        return $this;
    }

    /**
     * @param int $year
     * @return BookBuilderInterface
     */
    public function setYear(int $year): BookBuilderInterface
    {
        $this->book->year = $year;

        return $this;
    }

    /**
     * @param int $src
     * @return BookBuilderInterface
     */
    public function setSource(int $src): BookBuilderInterface
    {
        $this->book->source = in_array($src, Book::BOOK_SRC) ? $src : 0;

        return $this;
    }

    public function setSeries(string $series): BookBuilderInterface
    {
        $this->book->series = $series;

        return $this;
    }

    /**
     * @param string $urlToContext
     * @return BookBuilderInterface
     */
    public function setUrlToContext(string $urlToContext): BookBuilderInterface
    {
        $this->book->urlToContext = $urlToContext;

        return $this;
    }

    /**
     * @param string $urlToImage
     * @return BookBuilderInterface
     */
    public function setUrlToImage(string $urlToImage): BookBuilderInterface
    {
        $this->book->urlToImage = $urlToImage;

        return $this;
    }

    /**
     * @return Book
     */
    public function getBook(): Book
    {
        $result = $this->book;
        $this->create();

        return $result;
    }
}
