<?php


namespace App\Http\Controllers\Api\Category;


use App\DTO\ResponseDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateRequest;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\Http\JsonResponse;

class CategoryUpdateController extends Controller
{
    /**
     * @var CategoryRepositoryInterface
     */
    protected CategoryRepositoryInterface $repository;

    /**
     * CategoryUpdateController constructor.
     * @param CategoryRepositoryInterface $repository
     */
    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param UpdateRequest $request
     * @return JsonResponse
     */
    public function __invoke(UpdateRequest $request): JsonResponse
    {
        return response()->json(
            new ResponseDTO($this->repository->update($request->validated()))
        );
    }
}
