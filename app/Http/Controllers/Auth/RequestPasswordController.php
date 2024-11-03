<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Routing\Controller as BaseController;

class RequestPasswordController extends BaseController
{
    use ApiResponser;

    public function reset(Request $request): JsonResponse
    {
        $data = $this->validate($request, [
            'token' => 'required|string',
            'password' => [
                'required',
                'string',
                'min:10',             // must be at least 10 characters in length
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                'regex:/[@$!%*#?&]/', // must contain a special character
            ],
            'password_confirm' => 'required|same:password',

        ]);
        try {
            JWTAuth::getToken();
            JWTAuth::parseToken()->authenticate();
            if (! $request->user()) {
                return $this->errorResponse('Invalid token', 401);
            }

            $request->user()->password = Hash::make($data['password']);
            $request->user()->save();

            return $this->successResponse(
                $request->user(),
                'Success, your password have been reset'
            );
        } catch (TokenExpiredException $exception) {
            return $this->errorResponse(
                'Token has expired',
                400
            );
        } catch (\Throwable $exception) {
            return $this->errorResponse(
                'Generic error occurred when system trying reset your password, try again later',
                400
            );
        }
    }
}
