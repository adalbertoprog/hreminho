<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $q = Vehicle::query();

        if ($request->status) {
            $q->where('status', $request->status);
        }
        if ($request->search) {
            $term = '%' . $request->search . '%';
            $q->where(fn($s) => $s->where('plate', 'like', $term)
                                  ->orWhere('brand', 'like', $term)
                                  ->orWhere('model', 'like', $term));
        }

        return response()->json(['data' => $q->orderBy('plate')->get()->map(fn($v) => $this->format($v))]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'plate'  => 'required|string|max:20|unique:vehicles,plate',
            'brand'  => 'nullable|string|max:100',
            'model'  => 'nullable|string|max:100',
            'year'   => 'nullable|integer|min:1950|max:' . (date('Y') + 1),
            'type'   => 'nullable|in:van,truck,car,other',
            'status' => 'nullable|in:active,maintenance,inactive',
            'notes'  => 'nullable|string',
        ]);

        $vehicle = Vehicle::create($data);
        return response()->json(['data' => $this->format($vehicle)], 201);
    }

    public function update(Request $request, Vehicle $vehicle): JsonResponse
    {
        $data = $request->validate([
            'plate'  => 'sometimes|required|string|max:20|unique:vehicles,plate,' . $vehicle->id,
            'brand'  => 'nullable|string|max:100',
            'model'  => 'nullable|string|max:100',
            'year'   => 'nullable|integer|min:1950|max:' . (date('Y') + 1),
            'type'   => 'nullable|in:van,truck,car,other',
            'status' => 'nullable|in:active,maintenance,inactive',
            'notes'  => 'nullable|string',
        ]);

        $vehicle->update($data);
        return response()->json(['data' => $this->format($vehicle->fresh())]);
    }

    public function destroy(Vehicle $vehicle): JsonResponse
    {
        $vehicle->delete();
        return response()->json(null, 204);
    }

    private function format(Vehicle $v): array
    {
        return [
            'id'     => $v->id,
            'plate'  => $v->plate,
            'brand'  => $v->brand,
            'model'  => $v->model,
            'year'   => $v->year,
            'type'   => $v->type,
            'status' => $v->status,
            'notes'  => $v->notes,
            'label'  => trim("{$v->brand} {$v->model}") ?: $v->plate,
        ];
    }
}
