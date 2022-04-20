<?php
declare( strict_types = 1 );

namespace App\Repositories\Eloquent;

use App\Models\Category;
use App\Repositories\Interfaces\CategoryRepositoryInterface;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    /**
     * CategoryRepository constructor.
     */
    public function __construct()
    {
        parent::__construct(Category::class);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function listByCategory(int $id): mixed
    {
        return $this->model::where('id', $id)->with('books.image', 'books.authors')->with(['books' => function($query) use ($id) {
            $query->where('category_id', $id);
        }])->first();
    }
}
