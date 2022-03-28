<?php


namespace App\Http\Controllers\Api\Auth;


use App\Exceptions\ApiAuthException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;

class RegistrationController extends Controller
{
    /**
     * @var UserRepositoryInterface
     */
    protected UserRepositoryInterface $repository;

    /**
     * RegistrationController constructor.
     * @param UserRepositoryInterface $repository
     */
    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     * @throws ApiAuthException
     */
    public function __invoke(RegisterRequest $request): JsonResponse
    {
        $user = $this->repository->store([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password'))
        ]);

        if (!$user) {
            throw new ApiAuthException(
                $this->filterErrorMessage(trans('auth.registration.failed')),
                'context:' . $request->all()
            );
        }

        return response()->json(['result' => $user], 201);
    }
}
