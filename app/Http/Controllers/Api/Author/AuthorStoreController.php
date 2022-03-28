<?php


namespace App\Http\Controllers\Api\Author;


use App\DTO\ResponseDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Author\StoreRequest;
use App\Repositories\Interfaces\AuthorRepositoryInterface;
use Illuminate\Http\JsonResponse;

class AuthorStoreController extends Controller
{
    /**
     * @var AuthorRepositoryInterface
     */
    protected AuthorRepositoryInterface $repository;

    /**
     * AuthorStoreController constructor.
     * @param AuthorRepositoryInterface $repository
     */
    public function __construct(AuthorRepositoryInterface $repository)
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
