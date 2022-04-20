<?php

namespace App\Repositories\Eloquent;

use App\Models\Book;
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
        return $this->model::with('book', 'book.authors', 'book.category', 'book.files')
            ->orderBy('order', 'asc')
            ->get();
    }

    /**
     * @param int $bookId
     */
    public function removeBookFromQueue(int $bookId)
    {
        return $this->model::where('book_id', $bookId)->delete();
    }

    /**
     * @param int $bookId
     * @return mixed
     */
    public function bookAdd(int $bookId): mixed
    {
        if (Book::where('id',$bookId)->count() > 0) {
            return $this->model::create([
                'book_id' => $bookId,
                'order' => $this->model::max('order') + 1,
            ]);
        }
        throw new \Exception('Книга с данным ID не найдена');
    }

    /**
     * @param array $bookOrder
     * @return mixed
     */
    public function changeOrder(array $bookOrder): mixed
    {
        try {
            foreach ($bookOrder as $index => $item) {
                $this->model::where('book_id', $item['id'])->update(['order' => $index]);
            }

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function clearAll(): bool
    {
        $this->model::truncate();

        return $this->model::count() === 0;
    }
}
