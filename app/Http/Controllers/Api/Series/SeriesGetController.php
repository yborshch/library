<?php

namespace App\Http\Controllers\Api\Series;

use App\DTO\ResponseDTO;
use App\Exceptions\ApiArgumentException;
use App\Http\Controllers\Controller;
use App\Models\Series;
use App\Repositories\Interfaces\SeriesRepositoryInterface;
use Illuminate\Http\JsonResponse;

class SeriesGetController extends Controller
{
    /**
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
     * @param int $id
     * @return JsonResponse
     * @throws ApiArgumentException
     */
    public function __invoke(int $id): JsonResponse
    {
        return response()->json(
            new ResponseDTO($this->repository->get($id))
        );
    }
}
