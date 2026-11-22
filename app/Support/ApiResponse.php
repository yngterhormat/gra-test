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
            'metadata' => null,
        ]);
    }

    public static function created($data, string $message = 'Created'): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'metadata' => null,
        ], 201);
    }

    public static function validationError(string $message = 'Validation failed'): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => null,
            'metadata' => null,
        ], 422);
    }

    public static function pagination($data, string $message = 'OK'): JsonResponse
    {
        $other = $data->toArray();
        unset($other['data']);
        return response()->json([
            'message' => $message,
            'data' => $data->items(),
            'metadata' => $other,
        ]);
    }
}
