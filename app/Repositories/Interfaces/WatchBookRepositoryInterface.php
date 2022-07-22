<?php

namespace App\Repositories\Interfaces;

use stdClass;

interface WatchBookRepositoryInterface
{
    /**
     * @param stdClass $book
     * @return bool
     */
    public function isExistRelationToBook(stdClass $book): bool;
}
