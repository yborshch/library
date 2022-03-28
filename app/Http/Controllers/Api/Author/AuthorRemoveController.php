<?php

namespace App\Http\Controllers\Api\Author;

use App\DTO\ResponseDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\RemoveRequest;
use App\Repositories\Interfaces\AuthorRepositoryInterface;
use Illuminate\Http\JsonResponse;

class AuthorRemoveController extends Controller
{
    /**
     * @var AuthorRepositoryInterface
     */
    protected AuthorRepositoryInterface $repository;

    /**
     * AuthorRemoveController constructor.
     * @param AuthorRepositoryInterface $repository
     */
    public function __construct(AuthorRepositoryInterface $repository)
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
