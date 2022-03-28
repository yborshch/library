<?php

namespace App\Http\Controllers\Api\Series;

use App\DTO\ResponseDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRequest;
use App\Repositories\Interfaces\SeriesRepositoryInterface;
use Illuminate\Http\JsonResponse;

class SeriesStoreController extends Controller
{
    /**
     * @var SeriesRepositoryInterface
     */
    protected SeriesRepositoryInterface $repository;

    /**
     * SeriesStoreController constructor.
     * @param SeriesRepositoryInterface $repository
     */
    public function __construct(SeriesRepositoryInterface $repository)
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
            new ResponseDTO($this->repository->store($request->validated())),
            201
        );
    }
}
