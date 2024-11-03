<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponser
{
    protected function successResponse($data, $message = null, $code = 200): JsonResponse
    {
        return response()->json([
            'status' => 'Success',
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    protected function errorResponse($message = null, $code = 500): JsonResponse
    {
        return response()->json([
            'status' => 'Error',
            'message' => $message,
            'data' => null,
        ], $code);
    }
}
