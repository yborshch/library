<?php

namespace App\Http\Controllers\Api\Context;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\ContextRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SetBookmarkController extends Controller
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
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        return response()->json(
            $this->repository->addBookmark($request)
        );
    }
}
