<?php


namespace App\Http\Controllers\Api\Category;


use App\DTO\ResponseDTO;
use App\Exceptions\ApiArgumentException;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\Http\JsonResponse;

class CategoryGetController extends Controller
{
    /**
     * @var CategoryRepositoryInterface
     */
    protected CategoryRepositoryInterface $repository;

    /**
     * @param CategoryRepositoryInterface $repository
     */
    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param int $id
     * @return JsonResponse
     * @throws ApiArgumentException
     */
    public function __invoke(int $id): JsonResponse
    {
        return response()->json(
            new ResponseDTO($this->repository->get($id))
        );
    }
}
