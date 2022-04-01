<?php

namespace App\Http\Controllers\Api\Queue;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\QueueRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class QueueClearAllController extends Controller
{
    /**
     * @var QueueRepositoryInterface
     */
    protected QueueRepositoryInterface $queueRepository;

    /**
     * @param QueueRepositoryInterface $queueRepository
     */
    public function __construct(QueueRepositoryInterface $queueRepository)
    {
        $this->queueRepository = $queueRepository;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        return response()->json([
            $this->queueRepository->clearAll()
        ]);
    }
}
