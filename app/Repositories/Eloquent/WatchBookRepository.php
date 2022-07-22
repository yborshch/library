<?php

namespace App\Repositories\Eloquent;

use App\Models\Book;
use App\Models\WatchBook;
use App\Repositories\Interfaces\WatchBookRepositoryInterface;
use stdClass;

class WatchBookRepository extends BaseRepository implements WatchBookRepositoryInterface
{
    /**
     * TagRepository constructor.
     */
    public function __construct()
    {
        parent::__construct(WatchBook::class);
    }

    /**
     * @param array $params
     * @return bool
     */
    public function isExist(array $params): bool
    {
        return $this->model::where($params['column'], $params['value'])->count() > 0;
    }

    /**
     * @param stdClass $book
     * @return bool
     */
    public function isExistRelationToBook(stdClass $book): bool
    {
        return Book::where([
            'title' => $book->title,
            'source_link' => $book->url,
        ])->count() > 0;
    }
}
