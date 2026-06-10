<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index(): JsonResponse
    {
        $settings = SystemSetting::orderBy('group')->orderBy('id')->get()
            ->groupBy('group')
            ->map(fn($group) => $group->map(fn($s) => [
                'key'         => $s->key,
                'value'       => $s->castValue(),
                'type'        => $s->type,
                'label'       => $s->label,
                'description' => $s->description,
            ])->values());

        return response()->json(['data' => $settings]);
    }

    public function update(Request $request): JsonResponse
    {
        $input = $request->validate([
            'settings'       => 'required|array',
            'settings.*.key' => 'required|string|exists:system_settings,key',
            'settings.*.value' => 'present|nullable',
        ]);

        foreach ($input['settings'] as $item) {
            SystemSetting::set($item['key'], $item['value'] ?? '');
        }

        SystemSetting::clearCache();

        return response()->json(['message' => 'Configurações guardadas.']);
    }
}
