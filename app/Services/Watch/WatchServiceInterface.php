<?php

namespace App\Services\Watch;

use App\Models\WatchAuthor;
use App\Services\Watch\Parser\ParserInterface;

interface WatchServiceInterface
{
    /**
     * @param string $url
     */
    public function parseAuthorPage(string $url): void;

    /**
     * @param WatchAuthor $author
     * @param ParserInterface $parser
     */
    public function run(WatchAuthor $author, ParserInterface $parser): void;
}
