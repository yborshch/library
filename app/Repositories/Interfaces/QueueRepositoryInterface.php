<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface QueueRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @return Collection
     */
    public function getAll(): Collection;

    /**
     * @param int $bookId
     */
    public function removeBookFromQueue(int $bookId);

    /**
     * @param int $bookId
     * @return mixed
     */
    public function bookAdd(int $bookId): mixed;

    /**
     * @param array $bookOrder
     * @return mixed
     */
    public function changeOrder(array $bookOrder): mixed;

    /**
     * @return bool
     */
    public function clearAll(): bool;
}
