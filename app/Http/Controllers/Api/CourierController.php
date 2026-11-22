<?php

namespace App\Http\Controllers\Api;

use App\Models\Courier;
use App\Support\ApiResponse;
use Illuminate\Contracts\Validation\Factory as Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourierController extends \App\Http\Controllers\Controller
{
    protected Validator $validator;

    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 15);
        $perPage = min(max((int) $perPage, 1), 100);

        $query = Courier::query();
        if ($name = $request->query('name')) {
            $name = str_replace(" ", "%", $name);
            $query->where('name', 'ILIKE', '%'.$name.'%');
        }

        if ($level = $request->query('level')) {
            $query->whereIn('level', array_map('intval', explode(',', $level)));
        }

        $orderBy = in_array($request->query('order_by'), ['id', 'name', 'level', 'created_at'])
            ? (string) $request->query('order_by')
            : 'created_at';

        $sortDirection = strtolower((string) $request->query('sort_by', 'desc')) === 'asc' ? 'asc' : 'desc';

        $couriers = $query->orderBy($orderBy, $sortDirection)->paginate($perPage);

        return ApiResponse::pagination($couriers);
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
            'level' => ['required', 'integer', 'min:0', 'max:255'],
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
            'level' => ['required', 'integer', 'min:0', 'max:255'],
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
