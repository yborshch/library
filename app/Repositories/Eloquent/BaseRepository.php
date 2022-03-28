<?php
declare( strict_types = 1 );

namespace App\Repositories\Eloquent;

use App\DTO\ListDTO;
use App\Exceptions\ApiArgumentException;
use App\Traits\CheckEnvironmentTrait;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;

class BaseRepository
{
    /**
     * Trait filter error message for prod environment
     */
    use CheckEnvironmentTrait;

    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     * @param string $model
     */
    public function __construct(string $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $value
     * @return Model
     * @throws ApiArgumentException
     */
    public function store(array $value): Model
    {
        try {
            return $this->model::create($value);
        } catch (Exception $e) {
            throw new ApiArgumentException(
                $this->filterErrorMessage(__METHOD__ . ', ' . trans('api.error.database_store')),
                'data => ' . json_encode($value)
            );
        }
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
        return $this->withPagination($request);
    }

    /**
     * @param Request $request
     * @return ListDTO
     * @throws ApiArgumentException
     */
    protected function withPagination(Request $request): ListDTO
    {
        $currentPage = $request->get('currentPage', 1);
        $perPage = $request->get('perPage', 10);
        $orderBy = $request->get('orderBy', 'desc');

        if (!$perPage || $perPage < 1 || !$this->model::validateOrder($orderBy)) {
            throw new ApiArgumentException(
                $this->filterErrorMessage(__METHOD__ . ', ' . trans('api.arguments.bad')),
                'data => ' . json_encode($request->all())
            );
        }

        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });

        $value = $this->model::orderBy('id', $orderBy)->paginate($perPage);

        return new ListDTO(
            $value->currentPage(),
            (int)$value->perPage(),
            $value->total(),
            $value->lastPage(),
            $orderBy,
            $value->items(),
        );
    }

    /**
     * @return ListDTO
     */
    protected function all(): ListDTO
    {
        return new ListDTO(
            0,
            0,
            0,
            0,
            '',
            $this->model::all()->toArray(),
        );
    }

    /**
     * @param array $value
     * @return Model
     * @throws ApiArgumentException
     */
    public function update(array $value): Model
    {
        $item = $this->model::find($value['id']);

        if (!$item) {
            throw new ApiArgumentException(
                $this->filterErrorMessage(__METHOD__ . ', ' . trans('api.id.not_exist')),
                'data => ' . json_encode($value)
            );
        }

        $item->update($value);

        return $item;
    }

    /**
     * @param int $id
     * @return Model
     * @throws ApiArgumentException
     */
    public function remove(int $id): Model
    {
        $item = $this->model::find($id);

        if (!$item) {
            throw new ApiArgumentException(
                $this->filterErrorMessage(__METHOD__ . ', ' . trans('api.id.not_exist')),
                'data => ' . $id
            );
        }

        $item->delete();

        return $item;
    }

    /**
     * @param int $id
     * @return Collection
     * @throws ApiArgumentException
     */
    public function get(int $id): Collection
    {
        $item = $this->model::where('id', $id)->get();

        if (!$item->count()) {
            throw new ApiArgumentException(
                $this->filterErrorMessage(__METHOD__ . ', ' . trans('api.id.not_exist')),
                'context: id = ' . $id
            );
        }

        return $item;
    }
}
