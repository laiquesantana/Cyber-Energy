<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponser
{
    protected function errorResponse($message, $code = 500): JsonResponse
    {
        return response()->json([
            'status' => $code,
            'message' => $message,
            'data' => null,
            'error' => true,
        ], $code);
    }

    protected function successResponse($data, $message = '', $code = 200): JsonResponse
    {
        return response()->json([
            'status' => $code,
            'message' => $message,
            'data' => $data,
            'error' => false,
        ], $code);
    }
}
