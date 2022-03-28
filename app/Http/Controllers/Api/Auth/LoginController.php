<?php


namespace App\Http\Controllers\Api\Auth;


use App\Exceptions\ApiAuthException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{

    /**
     * @var UserRepositoryInterface
     */
    protected UserRepositoryInterface $repository;

    /**
     * LoginController constructor.
     * @param UserRepositoryInterface $repository
     */
    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     * @throws ApiAuthException
     */
    public function __invoke(LoginRequest $request): JsonResponse
    {
        $user = $this->repository->getUserByColumn('email', $request->get('email'));

        if(!$user || !Hash::check($request->get('password'), $user->password)) {
            throw new ApiAuthException(
                $this->filterErrorMessage(trans('auth.failed')),
                'context:' . json_encode($request->all())
            );
        }

        return response()->json([
            'user' => $user,
            'token' => $user->createToken('Token')->accessToken
        ]);
    }
}
