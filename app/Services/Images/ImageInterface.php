<?php

namespace App\Services\Images;


use App\ValueObject\Book;

interface ImageInterface
{
    /**
     * @param Book $book
     * @return mixed
     */
    public function download(Book $book);
}
