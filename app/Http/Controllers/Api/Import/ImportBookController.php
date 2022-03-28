<?php


namespace App\Http\Controllers\Api\Import;


use App\Exceptions\ApiArgumentException;
use App\Http\Controllers\Controller;
use App\Jobs\Import\ImportFromLovereadInPdfJob;
use App\Jobs\Import\ImportFromLovereadInRawJob;
use App\Services\Import\BookService;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ImportBookController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function __invoke(Request $request): JsonResponse
    {
        $job = match ($request->get('type')) {
            'pdf' => new ImportFromLovereadInPdfJob(new BookService(), $request),
            'raw' => new ImportFromLovereadInRawJob(new BookService(), $request),
            default => throw new ApiArgumentException(
                $this->filterErrorMessage(__METHOD__ . ', ' . trans('api.import.type')),
                'data => ' . json_encode($request->all())
            )
        };

        dispatch($job->onQueue('import'));

        return response()->json(['message' => trans('api.import.success')], 201);
    }
}
