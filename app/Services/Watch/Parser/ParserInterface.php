<?php

namespace App\Services\Watch\Parser;

use App\Models\WatchAuthor;

interface ParserInterface
{
    /**
     * @param string $url
     */
    public function parseAuthorInfo(string $url): void;

    /**
     * @param WatchAuthor $author
     */
    public function parseBookList(WatchAuthor $author): void;


    /**
     * @param string $path
     * @param string $host
     * @return string
     */
    public function getBookCover(string $path, string $host): string;

}
