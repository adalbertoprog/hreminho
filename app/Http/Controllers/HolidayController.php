<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Holiday::query();

        if ($request->filled('year')) {
            $query->whereYear('date', $request->year);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $holidays = $query->orderBy('date')->get();

        return response()->json(['data' => $holidays->map(fn($h) => $this->format($h))]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'           => 'required|string|max:100',
            'date'           => 'required|date',
            'type'           => 'required|in:national,local',
            'repeats_yearly' => 'boolean',
        ]);

        $holiday = Holiday::create($data);
        return response()->json(['data' => $this->format($holiday)], 201);
    }

    public function update(Request $request, int $holidayId): JsonResponse
    {
        $holiday = Holiday::findOrFail($holidayId);

        $data = $request->validate([
            'name'           => 'sometimes|string|max:100',
            'date'           => 'sometimes|date',
            'type'           => 'sometimes|in:national,local',
            'repeats_yearly' => 'boolean',
        ]);

        $holiday->update($data);
        return response()->json(['data' => $this->format($holiday->fresh())]);
    }

    public function destroy(int $holidayId): JsonResponse
    {
        Holiday::findOrFail($holidayId)->delete();
        return response()->json(null, 204);
    }

    private function format(Holiday $h): array
    {
        return [
            'id'             => $h->id,
            'name'           => $h->name,
            'date'           => $h->date?->format('Y-m-d'),
            'date_formatted' => $h->date?->format('d/m/Y'),
            'type'           => $h->type,
            'repeats_yearly' => $h->repeats_yearly,
        ];
    }
}
