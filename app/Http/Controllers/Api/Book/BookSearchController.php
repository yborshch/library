<?php

namespace App\Http\Controllers\Api\Book;

use App\DTO\ResponseDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\ListRequest;
use App\Repositories\Interfaces\BookRepositoryInterface;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\BookListResource;


class BookSearchController extends Controller
{
    /**
     * @var BookRepositoryInterface
     */
    protected BookRepositoryInterface $repository;

    /**
     * BookRemoveController constructor
     * @param BookRepositoryInterface $repository
     */
    public function __construct(BookRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param ListRequest $request
     * @return JsonResponse
     */
    public function __invoke(ListRequest $request): JsonResponse
    {
        if (!empty($request->get('word', ''))) {
            $response = new BookListResource(
                $this->repository->search($request)
            );
        } else {
            $request->request->add(['perPage' => 12]);

            $response = new BookListResource(
                $this->repository->list($request)
            );
        }

        return response()->json(
            new ResponseDTO($response)
        );
    }
}
