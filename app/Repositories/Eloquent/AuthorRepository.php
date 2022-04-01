<?php
declare( strict_types = 1 );

namespace App\Repositories\Eloquent;

use App\DTO\ListDTO;
use App\Models\Author;
use App\Repositories\Interfaces\AuthorRepositoryInterface;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use stdClass;
use Symfony\Component\HttpFoundation\Request;

class AuthorRepository extends BaseRepository implements AuthorRepositoryInterface
{
    /**
     * AuthorRepository constructor.
     */
    public function __construct()
    {
        parent::__construct(Author::class);
    }

    /**
     * @param int $id
     * @return mixed|void
     */
    public function listByAuthor(int $id) {
        return $this->model::where('id', $id)->with(['books' => function($query) use ($id) {
            $query->where('author_id', $id);
        }])->first();
    }

    /**
     * @param Request $request
     * @return ListDTO
     */
    public function search(Request $request): ListDTO
    {
        $currentPage = $request->get('currentPage', 1);
        $perPage = $request->get('perPage', 10);
        $word = $request->get('word', '');

        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });

        $value = DB::table('authors')->where('firstname', 'LIKE', '%' . $word . '%')
            ->orWhere('lastname', 'LIKE', '%' . $word . '%')
            ->paginate($perPage);

        return new ListDTO(
            $value->currentPage(),
            (int)$value->perPage(),
            $value->total(),
            $value->lastPage(),
            '',
            $value->items(),
        );
    }
}
