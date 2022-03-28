<?php

namespace App\Http\Controllers\Api\Series;

use App\DTO\ResponseDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\RemoveRequest;
use App\Http\Requests\StoreRequest;
use App\Repositories\Interfaces\SeriesRepositoryInterface;
use Illuminate\Http\JsonResponse;

class SeriesRemoveController extends Controller
{
    /**
     * @var SeriesRepositoryInterface
     */
    protected SeriesRepositoryInterface $repository;

    /**
     * SeriesRemoveController constructor.
     * @param SeriesRepositoryInterface $repository
     */
    public function __construct(SeriesRepositoryInterface $repository)
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
