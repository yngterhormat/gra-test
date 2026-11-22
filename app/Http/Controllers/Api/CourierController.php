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

    public function index(): JsonResponse
    {
        $couriers = Courier::paginate();

        return ApiResponse::success($couriers);
    }

    public function show(Request $request, $id): JsonResponse
    {
        $courier = Courier::findOrFail($id);

        return ApiResponse::success($courier);
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

    public function update(Request $request, $id): JsonResponse
    {
        $courier = Courier::findOrFail($id);

        $validator = $this->validator->make($request->all(), [
            'name' => ['required', 'string', 'max:100'],
            'level' => ['required', 'integer', 'max:255'],
        ]);

        if ($validator->fails()) {
            return ApiResponse::validationError();
        }

        $courier->update($validator->validated());

        return ApiResponse::success($courier->fresh());
    }

    public function destroy(Request $request, $id): JsonResponse
    {
        $courier = Courier::findOrFail($id);

        $courier->delete();

        return ApiResponse::success($courier);
    }
}
