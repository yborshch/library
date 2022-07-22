<?php

namespace App\Http\Controllers\Api\Book;

use App\DTO\ResponseDTO;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\BookRepositoryInterface;
use Illuminate\Http\JsonResponse;

class BookRawController extends Controller
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
     * @param int $id
     * @return JsonResponse
     */
    public function __invoke(int $id): JsonResponse
    {
        return response()->json(
            new ResponseDTO($this->repository->getRaw($id))
        );
    }
}
