<?php

namespace App\Repositories\Eloquent;

use App\Models\WatchBook;
use App\Repositories\Interfaces\WatchBookRepositoryInterface;

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
}
