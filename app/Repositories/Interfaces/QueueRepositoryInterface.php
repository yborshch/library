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
}
