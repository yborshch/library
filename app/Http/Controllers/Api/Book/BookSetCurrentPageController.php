<?php

namespace App\Http\Controllers\Api\Book;

use App\DTO\ResponseDTO;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\BookRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class BookSetCurrentPageController extends Controller
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
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        return response()->json([
            'result' => $this->repository->setCurrentPage($request)
        ]);
    }
}
