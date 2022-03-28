<?php

namespace App\Http\Controllers\Api\Category;

use App\DTO\ResponseDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRequest;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\Http\JsonResponse;

class CategoryStoreController extends Controller
{
    /**
     * @var CategoryRepositoryInterface
     */
    protected CategoryRepositoryInterface $repository;

    /**
     * CategoryStoreController constructor.
     * @param CategoryRepositoryInterface $repository
     */
    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param StoreRequest $request
     * @return JsonResponse
     */
    public function __invoke(StoreRequest $request): JsonResponse
    {
        return response()->json(
            new ResponseDTO($this->repository->store($request->validated())),
            201
        );
    }
}
