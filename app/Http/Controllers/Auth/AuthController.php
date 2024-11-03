<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Traits\ApiResponser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Saas\Project\Dependencies\Adapters\Logger\MonologLogAdapter;
use Saas\Project\Modules\User\Generics\Entities\User as UserEntity;
use Saas\Project\Modules\User\UserByOwn\Creation\Requests\Request as RequestUser;
use Saas\Project\Modules\User\UserByOwn\Creation\UseCase;

class AuthController extends Controller
{
    use ApiResponser;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'emailVerify','health']]);
    }

    protected function health(): JsonResponse
    {
        return $this->successResponse('application ok');
    }

    public function register(Request $request)
    {
        $data = $this->validate($request, [
            "email" => 'required|email|unique:users',
            "first_name" => 'required|string',
            "last_name" => 'required|string',
            "password" => [
                'required',
                'string',
                'min:10',             // must be at least 10 characters in length
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                'regex:/[@$!%*#?&]/', // must contain a special character
            ],
            "password_confirm" => 'required|same:password',
        ]);
        $useCase = new UseCase(new UserRepository(), new MonologLogAdapter());

        $user = (new UserEntity())
            ->setEmail($data['email'])
            ->setFirstName($data['first_name'])
            ->setLastName($data['last_name'])
            ->setPassword($data['password']);

        $request = new RequestUser($user);

        $useCase->execute($request);

        return response(
            $useCase->getResponse()->getStatus()->getCode()
        );
    }

    public function login(Request $request)
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return $this->errorResponse('Unauthorized', 401);
        }

        $user = $request->user();
        return $this->respondWithToken($token);
    }

    public function refresh()
    {
        $token = auth()->refresh();

        return $this->respondWithToken($token);
    }

    public function logout()
    {
        auth()->logout();

        return response()->json([
            'message' => 'Logout with success!',
        ], 200);
    }

    protected function respondWithToken($token)
    {
        $data = ([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 43000,
            'user' => Auth::user()
        ]);

        return $this->successResponse($data, 'login success');
    }
}
