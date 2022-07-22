<?php

namespace App\Http\Controllers\Api\Import;

use App\Http\Controllers\Controller;
use App\Http\Resources\IsExistWatchBookRelationToBookResource;
use App\Repositories\Interfaces\WatchAuthorRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

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
     * @param Request $request
     * @param $site
     * @return JsonResponse
     */
    public function __invoke(Request $request, $site): JsonResponse
    {
        $response = new IsExistWatchBookRelationToBookResource(
            $this->bookRepository->getImportedBooks($site)
        );

        return response()->json(
            $response,
            201
        );
    }
}
