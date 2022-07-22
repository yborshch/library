<?php

namespace App\Http\Controllers\Api\Context;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\ContextRepositoryInterface;
use Illuminate\Http\JsonResponse;

class ClearBookmarkController extends Controller
{
    /**
     * @var ContextRepositoryInterface
     */
    protected ContextRepositoryInterface $repository;

    /**
     * @param ContextRepositoryInterface $repository
     */
    public function __construct(ContextRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function __invoke($id): JsonResponse
    {
        return $this->repository->clearBookmark($id)
            ? response()->json()
            : response()->json('', 404);
    }
}
