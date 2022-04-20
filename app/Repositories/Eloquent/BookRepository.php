<?php
declare( strict_types = 1 );

namespace App\Repositories\Eloquent;

use App\DTO\ListDTO;
use App\Exceptions\ApiArgumentException;
use App\Models\Author;
use App\Models\Book;
use App\Models\File;
use App\Repositories\Interfaces\BookRepositoryInterface;
use App\ValueObject\Book as ValueBook;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;
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
            $book['read'] = 0;
            $book['source'] = $value['source'] ?? 1;
            $book['source_link'] = $value['source_link'] ?? '';
            $book['title'] = $value['title'];
            $book['type'] = $value['type'] ?? 'raw';
            $book['year'] = $value['year'];

            $savedBook = $this->model::create($book);
            $id = $savedBook->id;

            // Authors
            if ($this->isAuthorsArrayFromImport($value['author'])) {
                $arrayAuthorModels = (new AuthorRepository())->getOrCreate(
                    $this->preparationAuthorsForImport($value['author'])
                );

                foreach ($arrayAuthorModels as $arrayAuthorModel) {
                    $savedBook->authors()->attach($arrayAuthorModel->id);
                }
            } else {
                $arrayAuthorModels = $this->preparationAuthors($value['author']);

                foreach ($arrayAuthorModels as $arrayAuthorModel) {
                    $savedBook->authors()->attach($arrayAuthorModel['id']);
                }
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
     * @param array $authors
     * @return bool
     */
    protected function isAuthorsArrayFromImport(array $authors): bool
    {
        if (count($authors) > 0) {
            if (is_string(array_shift($authors))) {
                return true;
            }
        }
        return false;
    }

    /**
     * @throws ApiArgumentException
     */
    protected function storeFile(array $file, int $bookId, string $type): Model
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

    protected function preparationAuthors(array $values): array
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

    protected function preparationAuthorsForImport(array $values): stdClass
    {
        $authors = new stdClass();

        // Поля в свойстве field должны содержать имена ключей массивов из свойства values
        foreach ($values as $value) {
            $explode = explode(' ', $value);
            switch (count($explode)) {
                case 2:
                    // Пример: Людмила Мартова
                    $authors->values[] = [
                        'firstname' => $explode[0],
                        'lastname' => $explode[1],
                    ];
                    break;
                case 3:
                    // Пример: Галина Владимировна Романова
                    $authors->values[] = [
                        'firstname' => $explode[0],
                        'lastname' => $explode[2],
                    ];
                    break;
                default:
                    // Пример: Анна и Сергей Литвиновы
                    $lastname = end($explode);
                    $firstname = implode(' ', array_slice($explode, 0, -1));
                    $authors->values[] = [
                        'firstname' => $firstname,
                        'lastname' => $lastname,
                    ];
            }
        }

        return $authors;
    }

    public function getImportedBooks(): Collection
    {
        return $this->model::where('source', ValueBook::BOOK_IMPORT)
            ->with('authors', 'image')
            ->get();
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
        $item = $this->model::with('authors', 'category', 'files', 'series', 'queue')
            ->where('id', $bookId)
            ->get();

        if (!$item->count()) {
            throw new ApiArgumentException(
                $this->filterErrorMessage(__METHOD__ . ', ' . trans('api.id.not_exist')),
                'context: id = ' . $bookId
            );
        }

        return $item;
    }

    /**
     * @param int $id
     * @return Model
     * @throws ApiArgumentException
     */
    public function remove(int $id): Model
    {
        $book = $this->model::find($id);

        if (!$book) {
            throw new ApiArgumentException(
                $this->filterErrorMessage(__METHOD__ . ', ' . trans('api.id.not_exist')),
                'data => ' . $id
            );
        }

        foreach ($book->files as $file) {
            $this->removeFile($file);
        }

        $this->removeBookFromFiles($book);
        $this->removeBookFromMessages($book);
        $this->removeBookFromQueue($book);
        $this->removeBookAuthor($book);
        $this->removeBookTag($book);
        $book->delete();

        return $book;
    }

    protected function removeFile(File $file)
    {
        if (isset($file->image) && isset($file->filename) && isset($file->extension)) {
            $pathToFolder = $file->image ? '/images/books/' : '/storage/';
            if (file_exists(public_path($pathToFolder) . $file->filename . '.' . $file->extension)) {
                unlink(public_path($pathToFolder) . $file->filename . '.' . $file->extension);
            }
        }
    }

    /**
     * @param Book $book
     */
    protected function removeBookFromMessages(Book $book): void
    {
        $book->messages()->delete();
    }

    /**
     * @param Book $book
     */
    protected function removeBookFromQueue(Book $book): void
    {
        $book->queue()->delete();
    }

    /**
     * @param Book $book
     */
    protected function removeBookAuthor(Book $book): void
    {
        $book->authors()->detach();
    }

    /**
     * @param Book $book
     */
    protected function removeBookTag(Book $book): void
    {
        $book->tags()->detach();
    }

    /**
     * @param Book $book
     */
    protected function removeBookFromFiles(Book $book): void
    {
        $book->files()->delete();
    }

    /**
     * @param Request $request
     * @return ListDTO
     */
    public function search(Request $request): ListDTO
    {
        $word = $request->get('word', '');

        $ids = DB::table('books')
            ->join('author_book', 'author_book.book_id', '=', 'books.id')
            ->join('authors', 'author_book.author_id', '=', 'authors.id')
            ->where('authors.firstname', 'LIKE', '%' . $word . '%')
            ->orWhere('authors.lastname', 'LIKE', '%' . $word . '%')
            ->orWhere('books.title', 'LIKE', '%' . $word . '%')
            ->pluck('books.id');

        $value = $this->model::with('authors', 'image', 'queue')
            ->whereIn('id', $ids)
            ->paginate();

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
