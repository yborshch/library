<?php
declare( strict_types = 1 );

namespace App\Repositories\Eloquent;

use App\DTO\ListDTO;
use App\Exceptions\ApiArgumentException;
use App\Models\Author;
use App\Models\Book;
use App\Repositories\Interfaces\BookRepositoryInterface;
use App\ValueObject\Book as ValueBook;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use stdClass;
use Symfony\Component\HttpFoundation\Request;

class BookRepository extends BaseRepository implements BookRepositoryInterface
{

    /**
     * BookRepository constructor.
     */
    public function __construct()
    {
        parent::__construct(Book::class);
    }

    /**
     * @param array $value
     * @return Model
     */
    public function store(array $value): Model
    {
        try {
            // Book
            $book = [];
            if (isset($value['category_id'])) {
                $book['category_id'] = $value['category_id'];
            }

            // Series
            if (isset($value['series'])) {
                $seriesModel = (new SeriesRepository())->getOrCreate($value['series']);
                $book['series_id'] = $seriesModel->id;
            }

            $book['current_page'] = 0;
            $book['description'] = $value['description'];
            $book['pages'] = $value['pages'];
            $book['readed'] = 0;
            $book['source'] = $value['source'] ?? 1;
            $book['source_link'] = $value['source_link'] ?? '';
            $book['title'] = $value['title'];
            $book['type'] = $value['type'] ?? 'raw';
            $book['year'] = $value['year'];

            $savedBook = $this->model::create($book);
            $id = $savedBook->id;

            // Authors
            $arrayAuthorModels = $this->preparationAuthors($value['author']);

            foreach ($arrayAuthorModels as $arrayAuthorModel) {
                $savedBook->authors()->attach($arrayAuthorModel['id']);
            }

            if (isset($value['file'])) {
                $this->storeFile((array) $value['file'], $id, 'context');
            }

            if (isset($value['image'])) {
                $this->storeFile((array) $value['image'], $id, 'image');
            }

            $photo = $savedBook->image;
            $savedBook->image = $photo;

            $authors = $savedBook->authors;
            $savedBook->authors = $authors;

            return $savedBook;
        } catch (\Exception $e) {
            Log::critical(__METHOD__, [__LINE__ => $e->getMessage()]);
        }
        return new Book();
    }

    /**
     * @throws ApiArgumentException
     */
    public function storeFile(array $file, int $bookId, string $type): Model
    {
        $forStore = [
            'book_id' => $bookId,
            'filename' => $file['filename'],
            'image' => $type === 'image' ? 1 : 0,
            'context' => $type === 'context' ? 1 : 0,
            'extension' => $file['extension'],
        ];

        return (new FileRepository())->store($forStore);
    }

    public function preparationAuthors(array $values): array
    {
        $authors = [];

        foreach ($values as $value) {
            if ($value['new']) {
                $authors[] = Author::firstOrCreate(
                    ['firstname' => $value['firstname']],
                    ['lastname' => $value['lastname']],
                )->toArray();
            } else {
                $authors[] = Author::where('id', '=', $value['id'])
                    ->first()
                    ->toArray();
            }
        }

        return $authors;
    }

    public function getImportedBooks(): Collection
    {
        return $this->model::where('source', ValueBook::BOOK_IMPORT)->with('authors', 'image')->get();
    }

    /**
     * @param string $sourceLink
     * @return bool
     */
    public function isExist(string $sourceLink): bool
    {
        return $this->model::where('source_link', $sourceLink)->count() > 0;
    }

    /**
     * @param Request $request
     * @return ListDTO
     * @throws ApiArgumentException
     */
    public function list(Request $request): ListDTO
    {
        if ($request->get('all') && $request->get('all') === 'true') {
            return $this->all();
        }

        return $this->withPagination($request, ['authors', 'image', 'queue']);
    }

    /**
     * @param int $bookId
     * @return Collection
     * @throws ApiArgumentException
     */
    public function getBookDetail(int $bookId): Collection
    {
        $item = $this->model::with('authors', 'category', 'image', 'series', 'queue')->where('id', $bookId)->get();

        if (!$item->count()) {
            throw new ApiArgumentException(
                $this->filterErrorMessage(__METHOD__ . ', ' . trans('api.id.not_exist')),
                'context: id = ' . $bookId
            );
        }

        return $item;
    }
}
