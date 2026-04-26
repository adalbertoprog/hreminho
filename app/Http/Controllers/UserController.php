<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = User::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('name', 'like', "%{$s}%")
                                      ->orWhere('email', 'like', "%{$s}%"));
        }
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $rows = $query->orderBy('name')->paginate($request->get('per_page', 15));

        return response()->json([
            'data' => $rows->map(fn($u) => $this->format($u)),
            'meta' => [
                'current_page' => $rows->currentPage(),
                'last_page'    => $rows->lastPage(),
                'from'         => $rows->firstItem(),
                'to'           => $rows->lastItem(),
                'total'        => $rows->total(),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:users,email',
            'role'                  => 'required|in:admin,hr,employee',
            'password'              => 'required|string|min:8|confirmed',
        ]);

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        return response()->json(['data' => $this->format($user)], 201);
    }

    public function show(User $user): JsonResponse
    {
        return response()->json(['data' => $this->format($user)]);
    }

    public function update(Request $request, User $user): JsonResponse
    {
        $data = $request->validate([
            'name'     => 'sometimes|string|max:255',
            'email'    => ['sometimes', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'role'     => 'sometimes|in:admin,hr,employee',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return response()->json(['data' => $this->format($user->fresh())]);
    }

    public function destroy(Request $request, User $user): JsonResponse
    {
        if ($user->id === $request->user()->id) {
            return response()->json(['message' => 'Não podes eliminar a tua própria conta.'], 403);
        }

        $user->delete();

        return response()->json(['message' => 'Utilizador eliminado com sucesso.']);
    }

    private function format(User $u): array
    {
        return [
            'id'         => $u->id,
            'name'       => $u->name,
            'email'      => $u->email,
            'role'       => $u->role,
            'created_at' => $u->created_at?->toDateTimeString(),
        ];
    }
}
