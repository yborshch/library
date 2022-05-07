<?php


namespace App\Http\Controllers\Api\Category;


use App\DTO\ResponseDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\ListRequest;
use App\Http\Resources\IsHasBooksResource;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\Http\JsonResponse;

class CategoryListController extends Controller
{
    /**
     * @var CategoryRepositoryInterface
     */
    protected CategoryRepositoryInterface $repository;

    /**
     * CategoryListController constructor.
     * @param CategoryRepositoryInterface $repository
     */
    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param ListRequest $request
     * @return JsonResponse
     */
    public function __invoke(ListRequest $request): JsonResponse
    {
        if (!$request->get('all')) {
            $response = new IsHasBooksResource(
                $this->repository->list($request)
            );
        } else {
            $response = $this->repository->list($request);
        }

        return response()->json(
            new ResponseDTO($response)
        );
    }
}
