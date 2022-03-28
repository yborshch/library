<?php


namespace App\Http\Controllers\Api\Author;


use App\DTO\ResponseDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Author\UpdateRequest;
use App\Repositories\Interfaces\AuthorRepositoryInterface;
use Illuminate\Http\JsonResponse;

class AuthorUpdateController extends Controller
{
    /**
     * @var AuthorRepositoryInterface
     */
    protected AuthorRepositoryInterface $repository;

    /**
     * AuthorUpdateController constructor.
     * @param AuthorRepositoryInterface $repository
     */
    public function __construct(AuthorRepositoryInterface $repository)
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
