<?php


namespace App\Http\Controllers\Api\Author;


use App\DTO\ResponseDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\ListRequest;
use App\Http\Resources\IsHasBooksResource;
use App\Repositories\Interfaces\AuthorRepositoryInterface;
use Illuminate\Http\JsonResponse;

class AuthorListController extends Controller
{
    /**
     * @var AuthorRepositoryInterface
     */
    protected AuthorRepositoryInterface $repository;

    /**
     * AuthorListController constructor.
     * @param AuthorRepositoryInterface $repository
     */
    public function __construct(AuthorRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param ListRequest $request
     * @return JsonResponse
     */
    public function __invoke(ListRequest $request): JsonResponse
    {

        if (!$request->get('all')) {
            $response = new IsHasBooksResource(
                $this->repository->list($request)
            );
        } else {
            $response = $this->repository->list($request);
        }

        return response()->json(
            new ResponseDTO($response)
        );
    }
}
