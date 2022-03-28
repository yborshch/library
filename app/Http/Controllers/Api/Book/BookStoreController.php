<?php

namespace App\Http\Controllers\Api\Book;

use App\DTO\ResponseDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Book\StoreRequest;
use App\Repositories\Interfaces\BookRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class BookStoreController extends Controller
{
    /**
     * @var BookRepositoryInterface
     */
    protected BookRepositoryInterface $repository;

    /**
     * BookStoreController constructor
     * @param BookRepositoryInterface $repository
     */
    public function __construct(BookRepositoryInterface $repository)
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
            new ResponseDTO($this->repository->store($request->all())),
            201
        );
    }
}
