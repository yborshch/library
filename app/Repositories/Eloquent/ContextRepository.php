<?php
declare( strict_types = 1 );

namespace App\Repositories\Eloquent;

use App\Models\Context;
use App\Repositories\Interfaces\ContextRepositoryInterface;
use App\ValueObject\Book;
use Carbon\Carbon;

class ContextRepository extends BaseRepository implements ContextRepositoryInterface
{
    /**
     * AuthorRepository constructor.
     */
    public function __construct()
    {
        parent::__construct(Context::class);
    }

    /**
     * @param Book $book
     * @return bool
     */
    public function storeContext(Book $book): bool
    {
        $result = [];
        foreach ($book->context as $page => $text) {
            $result[] = [
                'book_id' => $book->id,
                'page' => $page,
                'text' => json_encode($text, JSON_UNESCAPED_UNICODE),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }
        return $this->model::insert($result);
    }
}
