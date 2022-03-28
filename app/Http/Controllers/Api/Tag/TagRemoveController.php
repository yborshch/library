<?php

namespace App\Http\Controllers\Api\Tag;

use App\DTO\ResponseDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\RemoveRequest;
use App\Repositories\Interfaces\TagRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class TagRemoveController extends Controller
{
    /**
     * @var TagRepositoryInterface
     */
    protected TagRepositoryInterface $repository;

    /**
     * TagRemoveController constructor.
     * @param TagRepositoryInterface $repository
     */
    public function __construct(TagRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param RemoveRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(RemoveRequest $request): JsonResponse
    {
        return response()->json(
            new ResponseDTO($this->repository->remove($request->get('id')))
        );
    }
}
