<?php

namespace App\Http\Controllers\Api\Book;

use App\DTO\ResponseDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Book\UpdateRequest;
use App\Repositories\Interfaces\BookRepositoryInterface;
use Illuminate\Http\JsonResponse;

class BookUpdateController extends Controller
{
    /**
     * @var BookRepositoryInterface
     */
    protected BookRepositoryInterface $repository;

    /**
     * @param BookRepositoryInterface $repository
     */
    public function __construct(BookRepositoryInterface $repository)
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
