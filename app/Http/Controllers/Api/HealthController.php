<?php

namespace App\Http\Controllers\Api;

use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

class HealthController extends \App\Http\Controllers\Controller
{
    public function check(): JsonResponse
    {
        return ApiResponse::success([
            'status' => 'ok',
        ]);
    }
}
