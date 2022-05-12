<?php

namespace App\Http\Controllers\Api\Import;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\WatchAuthorRepositoryInterface;
use Illuminate\Http\JsonResponse;

class ImportGetController extends Controller
{
    /**
     * @var WatchAuthorRepositoryInterface
     */
    protected WatchAuthorRepositoryInterface $bookRepository;

    /**
     * @param WatchAuthorRepositoryInterface $bookRepository
     */
    public function __construct(WatchAuthorRepositoryInterface $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    /**
     * @return JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        return response()->json(
            $this->bookRepository->getImportedBooks(),
            201
        );
    }
}
