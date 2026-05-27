<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeAssociationController extends Controller
{
    /**
     * Associate the authenticated user to an employee record by employee code.
     *
     * POST /api/v1/employee-portal/associate
     * Body: { "code": "FUN0590" }
     */
    public function associate(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50',
        ]);

        $user = Auth::user();
        $code = strtoupper(trim($request->input('code')));

        // Find employee by code (case-insensitive)
        $employee = Employee::whereRaw('UPPER(code) = ?', [$code])->first();

        if (! $employee) {
            return response()->json([
                'message' => 'Código de funcionário não encontrado. Verifique o código e tente novamente.',
            ], 422);
        }

        // Check if already linked to a DIFFERENT user
        if ($employee->user_id && $employee->user_id !== $user->id) {
            return response()->json([
                'message' => 'Este código já está associado a outra conta. Contacte o departamento de RH.',
            ], 409);
        }

        // Already linked to this user — idempotent success
        if ($employee->user_id === $user->id) {
            return response()->json([
                'message' => 'Associação já efectuada.',
                'employee' => [
                    'id'        => $employee->id,
                    'code'      => $employee->code,
                    'full_name' => $employee->full_name,
                ],
            ]);
        }

        // Make the link
        $employee->user_id = $user->id;
        $employee->save();

        return response()->json([
            'message' => 'Conta associada com sucesso! Bem-vindo, ' . $employee->first_name . '.',
            'employee' => [
                'id'        => $employee->id,
                'code'      => $employee->code,
                'full_name' => $employee->full_name,
            ],
        ]);
    }
}
