<?php

namespace App\Http\Controllers\Api\Series;

use App\DTO\ResponseDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\ListRequest;
use App\Repositories\Interfaces\SeriesRepositoryInterface;
use Illuminate\Http\JsonResponse;

class SeriesListController extends Controller
{
    /**
     * @var SeriesRepositoryInterface
     */
    protected SeriesRepositoryInterface $repository;

    /**
     * SeriesListController constructor.
     * @param SeriesRepositoryInterface $repository
     */
    public function __construct(SeriesRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param ListRequest $request
     * @return JsonResponse
     */
    public function __invoke(ListRequest $request): JsonResponse
    {
        return response()->json(
            new ResponseDTO($this->repository->list($request))
        );
    }
}
