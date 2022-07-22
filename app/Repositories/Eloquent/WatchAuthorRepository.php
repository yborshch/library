<?php

namespace App\Repositories\Eloquent;

use App\Models\WatchAuthor;
use App\Repositories\Interfaces\WatchAuthorRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

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

    /**
     * @param string $site
     * @return array
     */
    public function getImportedBooks(string $site): array
    {
        $result = [];
        $result['authors'] = $this->model::where('source', $site)
            ->get()
            ->toArray();
        $ids = array_column($result['authors'], 'id');
        $result['books'] = DB::table('watch_books')
            ->whereIn('author_id', $ids)
            ->select('watch_books.*', 'watch_authors.lastname', 'watch_authors.firstname')
            ->rightJoin('watch_authors', 'watch_books.author_id', '=', 'watch_authors.id')
            ->orderBy('watch_books.created_at', 'desc')
            ->get()
            ->toArray();
        return $result;
    }
}
