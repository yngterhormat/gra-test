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
            'other' => null,
        ]);
    }

    public static function created($data, string $message = 'Created'): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'other' => null,
        ], 201);
    }

    public static function validationError(string $message = 'Validation failed'): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => null,
            'other' => null,
        ], 422);
    }

    public static function pagination($data, string $message = 'OK'): JsonResponse
    {
        $other = $data;
        unset($other['data']);
        return response()->json([
            'message' => $message,
            'data' => $data['data'],
            'other' => $other,
        ]);
    }
}
