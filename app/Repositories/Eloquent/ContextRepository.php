<?php
declare( strict_types = 1 );

namespace App\Repositories\Eloquent;

use App\Models\Context;
use App\Repositories\Interfaces\ContextRepositoryInterface;
use App\ValueObject\Book;
use Carbon\Carbon;
use Dflydev\DotAccessData\Data;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Request;

class ContextRepository extends BaseRepository implements ContextRepositoryInterface
{
    /**
     * AuthorRepository constructor.
     */
    public function __construct()
    {
        parent::__construct(Context::class);
    }

    /**
     * @param Book $book
     * @return bool
     */
    public function storeContext(Book $book): bool
    {
        $result = [];
        foreach ($book->context as $page => $text) {
            $result[] = [
                'book_id' => $book->id,
                'page' => $page + 1,
                'text' => json_encode($text, JSON_UNESCAPED_UNICODE),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }
        return $this->model::insert($result);
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function addBookmark(Request $request): bool
    {
        $context = $this->model::where('id', $request->get('id'))->first();
        if (is_null($context->bookmark)) {
            $context->bookmark = [$request->get('index')];

            return $context->save();
        }

        $result = [...$context->bookmark, $request->get('index')];
        sort($result);
        $context->bookmark = $result;

        return $context->save();
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function removeBookmark(Request $request): bool
    {
        $bookmarks = $this->model::where('id', $request->get('id'))->first();
        $result = [];
        foreach ($bookmarks->bookmark as $bookmark) {
            if ($bookmark !== $request->get('index')) {
                $result[] = $bookmark;
            }
        }
        $bookmarks->bookmark = count($result) > 0 ? $result : null;

        return $bookmarks->save();
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function clearBookmark(int $id): mixed
    {
        return $this->model::where('book_id', $id)->update([
            'bookmark' => null
        ]);
    }
}
