<?php

namespace App\Services\Watch\Parser\Sites;

use App\Models\WatchAuthor;
use App\Services\Watch\Notification\Telegram\Message;
use App\ValueObject\WatchBook;
use App\ValueObject\WatchSeries;

abstract class AbstractParser
{
    /**
     * @param WatchAuthor $author
     * @param WatchSeries $series
     * @param WatchBook $book
     * @return Message
     */
    public function makeMessage(WatchAuthor $author, WatchSeries $series, WatchBook $book): Message
    {
        $result = new Message();
        $result->author = $author->firstname . ' ' .$author->lastname;
        $result->series = $series->title ?? '';
        $result->bookImage = $book->image;
        $result->bookTitle = $book->title;
        $result->bookLink = $book->link;

        return $result;
    }
}
