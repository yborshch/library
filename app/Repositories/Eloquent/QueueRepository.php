<?php

namespace App\Repositories\Eloquent;

use App\Models\Queue;
use App\Repositories\Interfaces\QueueRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class QueueRepository extends BaseRepository implements QueueRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Queue::class);
    }

    /**
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->model::with('book', 'book.authors')->get();
    }

    /**
     * @param int $bookId
     */
    public function removeBookFromQueue(int $bookId)
    {
        return $this->model::where('book_id', $bookId)->delete();
    }
}
