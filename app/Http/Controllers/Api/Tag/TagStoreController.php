<?php


namespace App\Http\Controllers\Api\Tag;


use App\DTO\ResponseDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRequest;
use App\Repositories\Interfaces\TagRepositoryInterface;
use Illuminate\Http\JsonResponse;

class TagStoreController extends Controller
{
    /**
     * @var TagRepositoryInterface
     */
    protected TagRepositoryInterface $repository;

    /**
     * TagStoreController constructor.
     * @param TagRepositoryInterface $repository
     */
    public function __construct(TagRepositoryInterface $repository)
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
