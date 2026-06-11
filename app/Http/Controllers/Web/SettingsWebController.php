<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\PermissionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SettingsWebController extends Controller
{
    public function index()
    {
        Gate::authorize('manage-hr');
        return view('settings.index');
    }

    public function permissions(): JsonResponse
    {
        Gate::authorize('admin-only');
        return response()->json(PermissionService::matrix());
    }

    public function savePermissions(Request $request): JsonResponse
    {
        Gate::authorize('admin-only');

        $data = $request->validate([
            'permissions'   => ['required', 'array'],
            'permissions.*' => ['boolean'],
        ]);

        PermissionService::save($data['permissions']);

        return response()->json(['message' => 'Permissoes guardadas com sucesso.']);
    }
}
