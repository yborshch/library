<?php

namespace App\Http\Controllers\Api\Book;

use App\DTO\ResponseDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\RemoveRequest;
use App\Repositories\Interfaces\BookRepositoryInterface;
use Illuminate\Http\JsonResponse;

class BookRemoveController extends Controller
{
    /**
     * @var BookRepositoryInterface
     */
    protected BookRepositoryInterface $repository;

    /**
     * BookRemoveController constructor.
     * @param BookRepositoryInterface $repository
     */
    public function __construct(BookRepositoryInterface $repository)
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
