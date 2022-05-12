<?php

namespace App\Repositories\Eloquent;

use App\Models\WatchAuthor;
use App\Repositories\Interfaces\WatchAuthorRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class WatchAuthorRepository extends BaseRepository implements WatchAuthorRepositoryInterface
{
    /**
     * TagRepository constructor.
     */
    public function __construct()
    {
        parent::__construct(WatchAuthor::class);
    }

    /**
     * @param int $page
     * @param int $limit
     * @return Collection
     */
    public function getWatchAuthors(int $page = 1, int $limit = 1): Collection
    {
        return $this->model::where('active', true)
            ->limit($limit)
            ->offset($page * $limit)
            ->get();
    }

    /**
     * @param array $params
     * @return bool
     */
    public function isExist(array $params): bool
    {
        return $this->model::where($params['column'], $params['value'])->count() > 0;
    }

    public function getImportedBooks(): Collection
    {
        $sources = $this->model::select('source')->groupBy('source')->get()->toArray();
        $result = [];
        $result2 = [];
        foreach ($sources as $item) {
            $result[$item['source']] = $this->model::where('source', $item['source'])->get();
        }
        foreach ($result as $value) {
            foreach ($value as $author) {
                dump($author->with('watchBook')->get());
//                $result2[] = $author->with('watchBook')->get();
            }
        }
dd($result);

    }
}
