<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class BulkUserController extends Controller
{
    /**
     * POST /api/v1/employees/bulk-create-users
     * Cria utilizadores para todos os funcionarios activos sem user_id.
     */
    public function createEmployeeUsers(Request $request)
    {
        $employees = Employee::where('status', 'active')
                             ->whereNull('user_id')
                             ->get();

        $created = 0;
        $linked  = 0;
        $errors  = [];

        foreach ($employees as $emp) {
            try {
                $username = strtolower($emp->code);

                // Se ja existe utilizador com o email do funcionario, apenas associar
                $existingByEmail = $emp->email
                    ? User::where('email', $emp->email)->first()
                    : null;

                if ($existingByEmail) {
                    $emp->user_id = $existingByEmail->id;
                    $emp->save();
                    $linked++;
                    continue;
                }

                // Email real ou gerado internamente
                $email = $emp->email ?: "{$username}@hrelectrominho.local";
                if (User::where('email', $email)->exists()) {
                    $email = "{$username}.{$emp->id}@hrelectrominho.local";
                }

                $user = User::create([
                    'name'                 => $emp->full_name,
                    'email'               => $email,
                    'password'            => Hash::make('12345678'),
                    'role'                => 'employee',
                    'must_change_password' => true, // Força mudança de password no primeiro login
                ]);

                $emp->user_id = $user->id;
                $emp->save();
                $created++;

            } catch (\Throwable $e) {
                $errors[] = "{$emp->code}: {$e->getMessage()}";
            }
        }

        return response()->json([
            'created'  => $created,
            'linked'   => $linked,
            'errors'   => $errors,
            'message'  => "Concluido: {$created} utilizadores criados, {$linked} contas ligadas."
                        . (count($errors) ? ' ' . count($errors) . ' erros.' : ''),
        ]);
    }
}
