<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface WatchAuthorRepositoryInterface
{
    /**
     * @param int $page
     * @param int $limit
     * @return Collection
     */
    public function getWatchAuthors(int $page = 1, int $limit = 1): Collection;
}
