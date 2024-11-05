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

    public function register(Request $request): JsonResponse
    {
        try {
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

            $userEntity = (new UserEntity())
                ->setEmail($data['email'])
                ->setFirstName($data['first_name'])
                ->setLastName($data['last_name'])
                ->setPassword($data['password']);

            $requestUser = new RequestUser($userEntity);

            $useCase->execute($requestUser);

            $responseCode = $useCase->getResponse()->getStatus()->getCode();
            $responseMessage = $useCase->getResponse()->getStatus()->getMessage();

            if ($responseCode >= 200 && $responseCode < 300) {
                $credentials = ['email' => $data['email'], 'password' => $data['password']];
                if (!$token = auth()->attempt($credentials)) {
                    return $this->errorResponse('Unauthorized', 401);
                }
                return $this->respondWithToken($token);
            } else {
                return $this->errorResponse($responseMessage, $responseCode);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {

            return $this->errorResponse($e->errors(),422);
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function login(Request $request)
    {

        try {
            $credentials = $request->only(['email', 'password']);

            if (!$token = auth()->attempt($credentials)) {
                return $this->errorResponse('Invalid email or password.', 401);
            }

            return $this->respondWithToken($token);
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred during login.');
        }
    }
    public function refresh(): JsonResponse
    {
        try {
            $token = auth()->refresh();

            return $this->respondWithToken($token);
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred while refreshing token.', 500);
        }
    }
    public function logout(): JsonResponse
    {
        try {
            auth()->logout();

            return response()->json([
                'message' => 'Logout successful.',
            ], 200);
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred during logout.', 500);
        }
    }
    protected function respondWithToken($token): JsonResponse
    {
        $cookie = cookie(
            'jwt_token',
            $token,
            1440,        // Expiration in minutes (e.g., 24 hours)
            '/',         // Path
            null,        // Domain set to null to support localhost
            true,       // Secure - should be true in production with HTTPS
            false,        // HTTP Only
            false,       // Raw
            'none'        // SameSite attribute
        );

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => Auth::user(),
        ])->withCookie($cookie);
    }


}
