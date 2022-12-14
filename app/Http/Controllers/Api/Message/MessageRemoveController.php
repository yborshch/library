<?php

namespace App\Http\Controllers\Api\Message;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\MessageRepositoryInterface;
use Illuminate\Http\JsonResponse;

class MessageRemoveController extends Controller
{
    /**
     * @var MessageRepositoryInterface
     */
    protected MessageRepositoryInterface $messageRepository;

    /**
     * @param MessageRepositoryInterface $messageRepository
     */
    public function __construct(MessageRepositoryInterface $messageRepository)
    {
        $this->messageRepository = $messageRepository;
    }

    /**
     * @return JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        return response()->json([]);
    }
}
