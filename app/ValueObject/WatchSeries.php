<?php

namespace App\ValueObject;

class WatchSeries
{
    /**
     * @var int
     */
    public int $id = 0;

    /**
     * @var string
     */
    public string $title;

    /**
     * @var string
     */
    public string $link;

    /**
     * @var WatchBook[]
     */
    public array $books;
}
