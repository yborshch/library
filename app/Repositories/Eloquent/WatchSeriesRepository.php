<?php

namespace App\Repositories\Eloquent;

use App\Models\WatchSeries;
use App\Repositories\Interfaces\WatchSeriesRepositoryInterface;

class WatchSeriesRepository extends BaseRepository implements WatchSeriesRepositoryInterface
{
    /**
     * TagRepository constructor.
     */
    public function __construct()
    {
        parent::__construct(WatchSeries::class);
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
