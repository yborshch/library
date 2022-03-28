<?php


namespace App\Http\Controllers\Api\Tag;


use App\DTO\ResponseDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateRequest;
use App\Repositories\Interfaces\TagRepositoryInterface;
use Illuminate\Http\JsonResponse;

class TagUpdateController extends Controller
{
    /**
     * @var TagRepositoryInterface
     */
    protected TagRepositoryInterface $repository;

    /**
     * TagUpdateController constructor.
     * @param TagRepositoryInterface $repository
     */
    public function __construct(TagRepositoryInterface $repository)
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
