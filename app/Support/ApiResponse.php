<?php

namespace App\Support;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    public static function success($data, string $message = 'OK'): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
        ]);
    }

    public static function created($data, string $message = 'Created'): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
        ], 201);
    }

    public static function validationError(string $message = 'Validation failed'): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => null,
        ], 422);
    }
}
