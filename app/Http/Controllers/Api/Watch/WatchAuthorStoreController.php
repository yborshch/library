<?php

namespace App\Http\Controllers\Api\Watch;

use App\Http\Controllers\Controller;
use App\Jobs\Watch\ParsingWatchAuthorJob;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class WatchAuthorStoreController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        dispatch(
            (new ParsingWatchAuthorJob($request))->onQueue('watch-store')
        );

        return response()->json([], 201);
    }
}
