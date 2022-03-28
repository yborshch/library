<?php

namespace App\Http\Controllers\Api\Series;

use App\DTO\ResponseDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateRequest;
use App\Repositories\Interfaces\SeriesRepositoryInterface;
use Illuminate\Http\JsonResponse;

class SeriesUpdateController extends Controller
{
    /**
     * SeriesUpdateController constructor.
     * @var SeriesRepositoryInterface
     */
    protected SeriesRepositoryInterface $repository;

    /**
     * @param SeriesRepositoryInterface $repository
     */
    public function __construct(SeriesRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param UpdateRequest $request
     * @return JsonResponse
     */
    public function __invoke(UpdateRequest $request): JsonResponse
    {
        return response()->json(
            new ResponseDTO($this->repository->update($request->validated()))
        );
    }
}
