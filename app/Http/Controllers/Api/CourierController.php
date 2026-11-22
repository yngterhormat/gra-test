<?php

namespace App\Http\Controllers\Api;

use App\Models\Courier;
use App\Support\ApiResponse;
use Illuminate\Contracts\Validation\Factory as Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourierController
{
    protected Validator $validator;

    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

    public function store(Request $request): JsonResponse
    {
        $validator = $this->validator->make($request->all(), [
            'name' => ['required', 'string', 'max:100'],
            'level' => ['required', 'integer', 'max:255'],
        ]);

        if ($validator->fails()) {
            return ApiResponse::validationError();
        }

        $courier = Courier::create($validator->validated());

        return ApiResponse::created($courier);
    }
}
