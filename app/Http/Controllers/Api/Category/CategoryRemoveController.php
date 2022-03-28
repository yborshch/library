<?php

namespace App\Http\Controllers\Api\Category;

use App\DTO\ResponseDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\RemoveRequest;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\Http\JsonResponse;


class CategoryRemoveController extends Controller
{
    /**
     * @var CategoryRepositoryInterface
     */
    protected CategoryRepositoryInterface $repository;

    /**
     * CategoryRemoveController constructor.
     * @param CategoryRepositoryInterface $repository
     */
    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param RemoveRequest $request
     * @return JsonResponse
     */
    public function __invoke(RemoveRequest $request): JsonResponse
    {
        return response()->json(
            new ResponseDTO($this->repository->remove($request->get('id')))
        );
    }
}
