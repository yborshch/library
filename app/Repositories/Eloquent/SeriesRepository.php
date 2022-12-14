<?php
declare( strict_types = 1 );

namespace App\Repositories\Eloquent;

use App\Models\Series;
use App\Repositories\Interfaces\SeriesRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class SeriesRepository extends BaseRepository implements SeriesRepositoryInterface
{
    /**
     * SeriesRepository constructor.
     */
    public function __construct()
    {
        parent::__construct(Series::class);
    }

    /**
     * @param string $series
     * @return Model
     */
    public function getOrCreate(string $series): Model
    {
        return $this->model::firstOrCreate(['title' => $series]);
    }
}
